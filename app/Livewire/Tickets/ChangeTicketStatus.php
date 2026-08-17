<?php

namespace App\Livewire\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ChangeTicketStatus extends Component
{
    #[Locked]
    public int $ticketId;

    public string $status = '';

    public function mount(Ticket $ticket): void
    {
        Gate::authorize('updateStatus', $ticket);

        $this->ticketId = $ticket->id;
        $this->status = $ticket->status->value;
    }

   public function updateStatus(): void
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        Gate::authorize('updateStatus', $ticket);

        $allowedStatuses = array_map(
            fn (TicketStatus $status) => $status->value,
            $ticket->status->allowedTransitions()
        );

        $validated = $this->validate([
            'status' => [
                'required',
                Rule::in($allowedStatuses),
            ],
        ]);

        $newStatus = TicketStatus::from($validated['status']);

        $ticket->status = $newStatus;

        if ($newStatus === TicketStatus::RESOLVED) {
            $ticket->resolved_at = now();
        } elseif ($newStatus !== TicketStatus::CLOSED) {
            $ticket->resolved_at = null;
        }

        if ($newStatus === TicketStatus::CLOSED) {
            $ticket->closed_at = now();
        }

        $ticket->save();

        $this->status = $ticket->status->value;

        session()->flash(
            'status_success',
            'Estado actualizado correctamente.'
        );
    }

    public function render()
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        Gate::authorize('updateStatus', $ticket);

        return view('livewire.tickets.change-ticket-status', [
            'ticket' => $ticket,
            'availableStatuses' => $ticket->status->allowedTransitions(),
        ]);
    }
}