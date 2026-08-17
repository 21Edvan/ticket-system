<?php

namespace App\Livewire\Dashboard;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OperationalDashboard extends Component
{
    private function ticketQuery(): Builder
    {
        $user = Auth::user();

        $query = Ticket::query();

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isTechnician()) {
            return $query->where('assigned_to', $user->id);
        }

        return $query->where('user_id', $user->id);
    }

    public function render()
    {
        $user = Auth::user();

        $baseQuery = $this->ticketQuery();

        $activeStatuses = [
            TicketStatus::OPEN->value,
            TicketStatus::ASSIGNED->value,
            TicketStatus::IN_PROGRESS->value,
            TicketStatus::WAITING->value,
        ];

        $stats = [
            'total' => (clone $baseQuery)->count(),

            'active' => (clone $baseQuery)
                ->whereIn('status', $activeStatuses)
                ->count(),

            'in_progress' => (clone $baseQuery)
                ->where('status', TicketStatus::IN_PROGRESS->value)
                ->count(),

            'waiting' => (clone $baseQuery)
                ->where('status', TicketStatus::WAITING->value)
                ->count(),

            'critical' => (clone $baseQuery)
                ->where('priority', TicketPriority::CRITICAL->value)
                ->whereIn('status', $activeStatuses)
                ->count(),

            'resolved' => (clone $baseQuery)
                ->where('status', TicketStatus::RESOLVED->value)
                ->count(),
        ];

        $recentTickets = (clone $baseQuery)
            ->with([
                'category',
                'user',
                'technician',
            ])
            ->latest()
            ->limit(8)
            ->get();

        $unassignedTickets = collect();

        $technicians = collect();

        if ($user->isAdmin()) {
            $unassignedTickets = Ticket::query()
                ->with([
                    'category',
                    'user',
                ])
                ->whereNull('assigned_to')
                ->where('status', TicketStatus::OPEN->value)
                ->oldest()
                ->limit(5)
                ->get();

            $technicians = User::query()
                ->where('role', UserRole::TECHNICIAN->value)
                ->withCount([
                    'assignedTickets as active_tickets_count' => function ($query) use ($activeStatuses) {
                        $query->whereIn('status', $activeStatuses);
                    },

                    'assignedTickets as critical_tickets_count' => function ($query) use ($activeStatuses) {
                        $query
                            ->whereIn('status', $activeStatuses)
                            ->where('priority', TicketPriority::CRITICAL->value);
                    },
                ])
                ->orderByDesc('active_tickets_count')
                ->orderBy('name')
                ->get();
        }

        return view('livewire.dashboard.operational-dashboard', [
            'stats' => $stats,
            'recentTickets' => $recentTickets,
            'unassignedTickets' => $unassignedTickets,
            'technicians' => $technicians,
            'user' => $user,
        ]);
    }
}