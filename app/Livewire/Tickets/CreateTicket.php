<?php

namespace App\Livewire\Tickets;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;
use Throwable;

class CreateTicket extends Component
{
    use WithFileUploads;

    public string $category_id = '';

    public string $title = '';

    public string $description = '';

    public string $priority = 'medium';

    public array $attachments = [];

    public function removeAttachment(int $index): void
    {
        if (! isset($this->attachments[$index])) {
            return;
        }

        unset($this->attachments[$index]);

        $this->attachments = array_values(
            $this->attachments
        );

        $this->resetValidation('attachments');
        $this->resetValidation('attachments.*');
    }

    public function save()
    {
        $validated = $this->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'is_active',
                            true
                        )
                    ),
            ],

            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],

            'priority' => [
                'required',
                Rule::enum(TicketPriority::class),
            ],

            'attachments' => [
                'array',
                'max:5',
            ],

            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,txt',
                'max:10240',
            ],
        ], [
            'attachments.max' =>
                'Puedes adjuntar un máximo de 5 archivos.',

            'attachments.*.file' =>
                'Uno de los archivos seleccionados no es válido.',

            'attachments.*.mimes' =>
                'Solo se permiten imágenes, PDF, Word, Excel y archivos de texto.',

            'attachments.*.max' =>
                'Cada archivo puede pesar como máximo 10 MB.',
        ]);

        $storedPaths = [];

        DB::beginTransaction();

        try {

            $ticket = Ticket::create([
                'ticket_number' => $this->generateTicketNumber(),

                'user_id' => Auth::id(),

                'category_id' => $validated['category_id'],

                'assigned_to' => null,

                'title' => $validated['title'],

                'description' => $validated['description'],

                'priority' => TicketPriority::from(
                    $validated['priority']
                ),

                'status' => TicketStatus::OPEN,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Guardar adjuntos
            |--------------------------------------------------------------------------
            */

            foreach ($this->attachments as $file) {

                $extension = strtolower(
                    $file->getClientOriginalExtension()
                );

                $storedName = (string) Str::uuid();

                if ($extension !== '') {
                    $storedName .= '.'.$extension;
                }

                $path = $file->storeAs(
                    path: "tickets/{$ticket->id}",
                    name: $storedName,
                    options: 'attachments',
                );

                if (! $path) {
                    throw new RuntimeException(
                        'No se pudo almacenar uno de los archivos.'
                    );
                }

                $storedPaths[] = $path;

                $ticket->attachments()->create([
                    'ticket_comment_id' => null,

                    'uploaded_by' => Auth::id(),

                    'original_name' =>
                        $file->getClientOriginalName(),

                    'path' => $path,

                    'mime_type' => $file->getMimeType(),

                    'size' => $file->getSize(),
                ]);
            }


            DB::commit();

        } catch (Throwable $exception) {

            DB::rollBack();

            foreach ($storedPaths as $path) {
                Storage::disk('attachments')
                    ->delete($path);
            }

            report($exception);

            $this->addError(
                'attachments',
                'No se pudo crear el ticket. Inténtalo nuevamente.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Notificar administradores
        |--------------------------------------------------------------------------
        */

        try {

            $admins = User::query()
                ->where(
                    'role',
                    UserRole::ADMIN->value
                )
                ->where(
                    'id',
                    '!=',
                    Auth::id()
                )
                ->get();

            Notification::send(
                $admins,
                new TicketNotification(
                    ticket: $ticket,
                    kind: 'ticket_created',
                    title: 'Nuevo ticket',
                    message:
                        "Se creó el ticket {$ticket->ticket_number}: {$ticket->title}",
                )
            );

        } catch (Throwable $exception) {

            /*
             * Si falla una notificación,
             * el ticket ya está creado y no debe perderse.
             */
            report($exception);
        }


        session()->flash(
            'success',
            'Ticket creado correctamente.'
        );

        return $this->redirect(
            route(
                'tickets.show',
                $ticket
            ),
            navigate: true
        );
    }

    private function generateTicketNumber(): string
    {
        do {

            $ticketNumber =
                'TCK-'
                .now()->format('Ymd')
                .'-'
                .Str::upper(
                    Str::random(6)
                );

        } while (
            Ticket::where(
                'ticket_number',
                $ticketNumber
            )->exists()
        );

        return $ticketNumber;
    }

    public function render()
    {
        return view(
            'livewire.tickets.create-ticket',
            [
                'categories' => Category::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(),

                'priorities' => TicketPriority::cases(),
            ]
        );
    }
}