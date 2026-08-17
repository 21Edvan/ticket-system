<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class TicketActivity extends Component
{
    #[Locked]
    public int $ticketId;

    public function mount(Ticket $ticket): void
    {
        Gate::authorize('view', $ticket);

        $this->ticketId = $ticket->id;
    }

    #[On('ticket-activity-updated')]
    public function refreshActivity(): void
    {
        //
    }

    public function render()
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        Gate::authorize('view', $ticket);

        return view('livewire.tickets.ticket-activity', [
            'histories' => $ticket->histories()
                ->with('actor')
                ->latest()
                ->get(),
        ]);
    }
}