<?php

namespace App\Livewire\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Notifications\TicketNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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

        $oldStatus = $ticket->status;
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

        $recipients = collect([
            $ticket->user,
            $ticket->technician,
        ])
            ->filter()
            ->unique('id')
            ->reject(
                fn ($user) => $user->id === Auth::id()
            );

        Notification::send(
            $recipients,
            new TicketNotification(
                ticket: $ticket,
                kind: 'status_changed',
                title: 'Estado actualizado',
                message: "El ticket {$ticket->ticket_number} cambió de "
                    .$oldStatus->label()
                    .' a '
                    .$newStatus->label()
                    .'.',
            )
        );

        $this->status = $ticket->status->value;

        $this->dispatch('ticket-activity-updated');

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