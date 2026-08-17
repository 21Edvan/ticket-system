<?php

namespace App\Livewire\Tickets;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListTickets extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $priority = '';
    public string $category = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPriority(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'status',
            'priority',
            'category',
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $query = Ticket::query()
            ->with([
                'category',
                'user',
                'technician',
            ]);

        $user = Auth::user();

        if ($user->isAdmin()) {

            // El administrador ve todos los tickets.

        } elseif ($user->isTechnician()) {

            $query->where('assigned_to', $user->id);

        } else {

            $query->where('user_id', $user->id);

        }

        $tickets = $query
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query
                        ->where('ticket_number', 'like', '%'.$this->search.'%')
                        ->orWhere('title', 'like', '%'.$this->search.'%');
                });
            })
            ->when(
                $this->status,
                fn ($query) => $query->where('status', $this->status)
            )
            ->when(
                $this->priority,
                fn ($query) => $query->where('priority', $this->priority)
            )
            ->when(
                $this->category,
                fn ($query) => $query->where('category_id', $this->category)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.tickets.list-tickets', [
            'tickets' => $tickets,

            'categories' => Category::query()
                ->orderBy('name')
                ->get(),

            'statuses' => TicketStatus::cases(),

            'priorities' => TicketPriority::cases(),
        ]);
    }
}