<?php

namespace App\Livewire\Tickets;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AssignTechnician extends Component
{
    public Ticket $ticket;

    public string $technician_id = '';

    public function mount(Ticket $ticket): void
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $this->ticket = $ticket;

        $this->technician_id = $ticket->assigned_to
            ? (string) $ticket->assigned_to
            : '';
    }

    public function assign(): void
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $this->validate([
            'technician_id' => [
                'required',
                Rule::exists('users', 'id')->where(
                    'role',
                    UserRole::TECHNICIAN->value
                ),
            ],
        ]);

        $this->ticket->assigned_to = $validated['technician_id'];

        if ($this->ticket->status === TicketStatus::OPEN) {
            $this->ticket->status = TicketStatus::ASSIGNED;
        }

        $this->ticket->save();

        $this->ticket->refresh();
        $this->dispatch('ticket-activity-updated');

        session()->flash(
            'assignment_success',
            'Técnico asignado correctamente.'
        );
    }

    public function render()
    {
        return view('livewire.tickets.assign-technician', [
            'technicians' => User::query()
                ->where('role', UserRole::TECHNICIAN->value)
                ->orderBy('name')
                ->get(),
        ]);
    }
}