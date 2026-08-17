<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\TicketRead;
use App\Notifications\TicketNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;
use Throwable;

class TicketComments extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $ticketId;

    public int $unreadCount = 0;

    public string $body = '';

    public int $lastCommentId = 0;

    public array $attachments = [];


    public function mount(Ticket $ticket): void
    {
        Gate::authorize('view', $ticket);

        $this->ticketId = $ticket->id;

        $this->lastCommentId = (int) (
            $ticket->comments()->max('id') ?? 0
        );

        $this->updateUnreadCount();
    }


    public function updatedAttachments(): void
    {
        $this->validate(
            $this->attachmentRules(),
            $this->attachmentMessages()
        );
    }


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


    public function addComment(): void
    {
        $ticket = Ticket::with([
            'user',
            'technician',
        ])->findOrFail($this->ticketId);

        Gate::authorize('comment', $ticket);

        $cleanBody = trim($this->body);


        /*
        |--------------------------------------------------------------------------
        | Debe existir texto o al menos un archivo
        |--------------------------------------------------------------------------
        */

        if (
            $cleanBody === ''
            && count($this->attachments) === 0
        ) {
            $this->addError(
                'body',
                'Escribe un mensaje o adjunta un archivo.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Si escribe texto, mínimo 2 caracteres
        |--------------------------------------------------------------------------
        */

        if (
            $cleanBody !== ''
            && mb_strlen($cleanBody) < 2
        ) {
            $this->addError(
                'body',
                'El mensaje debe tener al menos 2 caracteres.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $validated = $this->validate(
            array_merge(
                [
                    'body' => [
                        'nullable',
                        'string',
                        'max:5000',
                    ],
                ],
                $this->attachmentRules()
            ),
            array_merge(
                [
                    'body.max' =>
                        'El mensaje no puede superar los 5000 caracteres.',
                ],
                $this->attachmentMessages()
            )
        );


        $storedPaths = [];

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Crear comentario
            |--------------------------------------------------------------------------
            */

            $comment = $ticket->comments()->create([
                'user_id' => Auth::id(),
                'body' => $cleanBody,
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
                    "tickets/{$ticket->id}/comments/{$comment->id}",
                    $storedName,
                    'attachments'
                );


                if (! $path) {
                    throw new RuntimeException(
                        'No se pudo almacenar uno de los archivos.'
                    );
                }


                $storedPaths[] = $path;


                $comment->attachments()->create([
                    'ticket_id' => $ticket->id,

                    'uploaded_by' => Auth::id(),

                    'original_name' => mb_substr(
                        $file->getClientOriginalName(),
                        0,
                        255
                    ),

                    'path' => $path,

                    'mime_type' => $file->getMimeType(),

                    'size' => $file->getSize(),
                ]);
            }


            DB::commit();

        } catch (Throwable $exception) {

            DB::rollBack();


            /*
             * Si se guardaron archivos antes del error,
             * eliminarlos físicamente.
             */
            foreach ($storedPaths as $path) {
                Storage::disk('attachments')
                    ->delete($path);
            }


            report($exception);


            $this->addError(
                'attachments',
                'No se pudo enviar el mensaje. Inténtalo nuevamente.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Notificaciones
        |--------------------------------------------------------------------------
        */

        try {

            $recipients = collect([
                $ticket->user,
                $ticket->technician,
            ])
                ->filter()
                ->unique('id')
                ->reject(
                    fn ($user) =>
                        $user->id === Auth::id()
                );


            if (
                $cleanBody === ''
                && count($this->attachments) > 0
            ) {
                $amount = count($this->attachments);

                $notificationMessage =
                    Auth::user()->name
                    .' compartió '
                    .($amount === 1
                        ? 'un archivo'
                        : "{$amount} archivos")
                    ." en {$ticket->ticket_number}.";
            } else {
                $notificationMessage =
                    Auth::user()->name
                    ." respondió en {$ticket->ticket_number}.";
            }


            Notification::send(
                $recipients,
                new TicketNotification(
                    ticket: $ticket,
                    kind: 'new_comment',
                    title: 'Nuevo comentario',
                    message: $notificationMessage,
                )
            );

        } catch (Throwable $exception) {

            /*
             * El comentario ya fue guardado.
             * Un fallo de notificación no debe eliminarlo.
             */
            report($exception);
        }


        /*
        |--------------------------------------------------------------------------
        | Actualizar estado del chat
        |--------------------------------------------------------------------------
        */

        $this->lastCommentId = $comment->id;

        $this->reset([
            'body',
            'attachments',
        ]);

        $this->resetValidation();

        $this->markAsRead();

        $this->dispatch('comments-updated');
    }


    public function refreshComments(): void
    {
        $ticket = Ticket::findOrFail(
            $this->ticketId
        );

        Gate::authorize('view', $ticket);


        $latestCommentId = (int) (
            $ticket->comments()->max('id') ?? 0
        );


        if ($latestCommentId > $this->lastCommentId) {

            $this->lastCommentId =
                $latestCommentId;

            $this->updateUnreadCount();

            $this->dispatch(
                'comments-updated'
            );
        }
    }


    public function markAsRead(): void
    {
        $ticket = Ticket::findOrFail(
            $this->ticketId
        );

        Gate::authorize('view', $ticket);


        $latestCommentId = $ticket
            ->comments()
            ->max('id');


        TicketRead::updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
            ],
            [
                'last_read_comment_id' =>
                    $latestCommentId,
            ]
        );


        $this->unreadCount = 0;
    }


    private function updateUnreadCount(): void
    {
        $lastReadCommentId = (int) (
            TicketRead::query()
                ->where(
                    'ticket_id',
                    $this->ticketId
                )
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->value(
                    'last_read_comment_id'
                )
                ?? 0
        );


        $this->unreadCount = Ticket::findOrFail(
            $this->ticketId
        )
            ->comments()
            ->where(
                'id',
                '>',
                $lastReadCommentId
            )
            ->where(
                'user_id',
                '!=',
                Auth::id()
            )
            ->count();
    }


    private function attachmentRules(): array
    {
        return [
            'attachments' => [
                'array',
                'max:5',
            ],

            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,txt',
                'max:10240',
            ],
        ];
    }


    private function attachmentMessages(): array
    {
        return [
            'attachments.max' =>
                'Puedes adjuntar un máximo de 5 archivos.',

            'attachments.*.file' =>
                'Uno de los archivos seleccionados no es válido.',

            'attachments.*.mimes' =>
                'Solo se permiten imágenes, PDF, Word, Excel y archivos de texto.',

            'attachments.*.max' =>
                'Cada archivo puede pesar como máximo 10 MB.',
        ];
    }


    public function render()
    {
        $ticket = Ticket::with([
            'technician',
        ])->findOrFail(
            $this->ticketId
        );

        Gate::authorize('view', $ticket);


        return view(
            'livewire.tickets.ticket-comments',
            [
                'ticket' => $ticket,

                'comments' => $ticket
                    ->comments()
                    ->with([
                        'user',
                        'attachments',
                    ])
                    ->oldest()
                    ->get(),
            ]
        );
    }
}