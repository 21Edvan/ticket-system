<?php

namespace App\Livewire\Tickets;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateTicket extends Component
{
    public string $category_id = '';
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';

    public ?string $createdTicketNumber = null;

    protected function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where('is_active', true),
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
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

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

        $this->createdTicketNumber = $ticket->ticket_number;

        $this->reset([
            'category_id',
            'title',
            'description',
        ]);

        $this->priority = TicketPriority::MEDIUM->value;
    }

    private function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'TCK-'
                .now()->format('Ymd')
                .'-'
                .Str::upper(Str::random(6));
        } while (
            Ticket::where('ticket_number', $ticketNumber)->exists()
        );

        return $ticketNumber;
    }

    public function render()
    {
        return view('livewire.tickets.create-ticket', [
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

            'priorities' => TicketPriority::cases(),
        ]);
    }
}