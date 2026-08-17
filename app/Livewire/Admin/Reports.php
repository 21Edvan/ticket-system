<?php

namespace App\Livewire\Admin;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Reports extends Component
{
    public string $period = '30';

    public string $categoryId = '';

    public string $technicianId = '';


    public function mount(): void
    {
        $this->authorizeAdmin();
    }


    public function resetFilters(): void
    {
        $this->period = '30';
        $this->categoryId = '';
        $this->technicianId = '';
    }


    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check()
            && Auth::user()->isAdmin(),
            403
        );
    }


    private function activeStatuses(): array
    {
        return [
            TicketStatus::OPEN->value,
            TicketStatus::ASSIGNED->value,
            TicketStatus::IN_PROGRESS->value,
            TicketStatus::WAITING->value,
        ];
    }


    private function periodRange(): ?array
    {
        $now = now();

        return match ($this->period) {
            '7' => [
                $now->copy()->subDays(6)->startOfDay(),
                $now->copy()->endOfDay(),
            ],

            '30' => [
                $now->copy()->subDays(29)->startOfDay(),
                $now->copy()->endOfDay(),
            ],

            '90' => [
                $now->copy()->subDays(89)->startOfDay(),
                $now->copy()->endOfDay(),
            ],

            'year' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfDay(),
            ],

            'all' => null,

            default => [
                $now->copy()->subDays(29)->startOfDay(),
                $now->copy()->endOfDay(),
            ],
        };
    }


    private function ticketQuery(
        string $dateColumn = 'created_at'
    ): Builder {
        $query = DB::table('tickets');


        /*
        |--------------------------------------------------------------------------
        | Período
        |--------------------------------------------------------------------------
        */

        if ($range = $this->periodRange()) {
            $query->whereBetween(
                $dateColumn,
                $range
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Categoría
        |--------------------------------------------------------------------------
        */

        if ($this->categoryId !== '') {
            $query->where(
                'category_id',
                (int) $this->categoryId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Técnico
        |--------------------------------------------------------------------------
        */

        if ($this->technicianId === 'unassigned') {

            $query->whereNull('assigned_to');

        } elseif ($this->technicianId !== '') {

            $query->where(
                'assigned_to',
                (int) $this->technicianId
            );
        }


        return $query;
    }


    private function averageResolutionMinutes(): ?float
    {
        $query = $this->ticketQuery('resolved_at')
            ->whereNotNull('resolved_at')
            ->select([
                'created_at',
                'resolved_at',
            ]);

        $minutes = [];

        foreach ($query->cursor() as $ticket) {

            if (!$ticket->created_at || !$ticket->resolved_at) {
                continue;
            }

            $createdAt = Carbon::parse(
                $ticket->created_at
            );

            $resolvedAt = Carbon::parse(
                $ticket->resolved_at
            );

            $minutes[] = $createdAt->diffInMinutes(
                $resolvedAt
            );
        }

        if (empty($minutes)) {
            return null;
        }

        return array_sum($minutes)
            / count($minutes);
    }


    private function formatDuration(
        ?float $minutes
    ): string {
        if ($minutes === null) {
            return '—';
        }

        $minutes = (int) round($minutes);


        if ($minutes < 60) {
            return $minutes.' min';
        }


        if ($minutes < 1440) {

            $hours = intdiv(
                $minutes,
                60
            );

            $remainingMinutes =
                $minutes % 60;

            if ($remainingMinutes === 0) {
                return $hours.' h';
            }

            return $hours.' h '
                .$remainingMinutes.' min';
        }


        $days = intdiv(
            $minutes,
            1440
        );

        $remainingHours = intdiv(
            $minutes % 1440,
            60
        );

        if ($remainingHours === 0) {
            return $days.' d';
        }

        return $days.' d '
            .$remainingHours.' h';
    }


    private function statusDistribution(
        int $totalCreated
    ): array {
        $raw = $this->ticketQuery('created_at')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status'
            );


        return collect(
            TicketStatus::cases()
        )
            ->map(function (
                TicketStatus $status
            ) use (
                $raw,
                $totalCreated
            ) {

                $total = (int) (
                    $raw[$status->value]
                    ?? 0
                );

                return [
                    'value' =>
                        $status->value,

                    'label' =>
                        $status->label(),

                    'total' =>
                        $total,

                    'percentage' =>
                        $totalCreated > 0
                            ? round(
                                ($total / $totalCreated)
                                * 100
                            )
                            : 0,
                ];
            })
            ->values()
            ->all();
    }


    private function priorityDistribution(
        int $totalCreated
    ): array {
        $raw = $this->ticketQuery('created_at')
            ->select(
                'priority',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('priority')
            ->pluck(
                'total',
                'priority'
            );


        return collect(
            TicketPriority::cases()
        )
            ->map(function (
                TicketPriority $priority
            ) use (
                $raw,
                $totalCreated
            ) {

                $total = (int) (
                    $raw[$priority->value]
                    ?? 0
                );

                return [
                    'value' =>
                        $priority->value,

                    'label' =>
                        $priority->label(),

                    'total' =>
                        $total,

                    'percentage' =>
                        $totalCreated > 0
                            ? round(
                                ($total / $totalCreated)
                                * 100
                            )
                            : 0,
                ];
            })
            ->values()
            ->all();
    }


    private function categoryDistribution(
        int $totalCreated
    ): array {
        $raw = $this->ticketQuery('created_at')
            ->select(
                'category_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('category_id')
            ->pluck(
                'total',
                'category_id'
            );


        if ($raw->isEmpty()) {
            return [];
        }


        $categories = Category::query()
            ->whereIn(
                'id',
                $raw->keys()
            )
            ->pluck(
                'name',
                'id'
            );


        return $raw
            ->map(function (
                $total,
                $categoryId
            ) use (
                $categories,
                $totalCreated
            ) {

                $total = (int) $total;

                return [
                    'id' =>
                        (int) $categoryId,

                    'name' =>
                        $categories[$categoryId]
                        ?? 'Categoría eliminada',

                    'total' =>
                        $total,

                    'percentage' =>
                        $totalCreated > 0
                            ? round(
                                ($total / $totalCreated)
                                * 100
                            )
                            : 0,
                ];
            })
            ->sortByDesc('total')
            ->take(8)
            ->values()
            ->all();
    }


    private function technicianPerformance(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Tickets creados/asignados durante el período
        |--------------------------------------------------------------------------
        */

        $assigned = $this->ticketQuery(
            'created_at'
        )
            ->whereNotNull('assigned_to')
            ->select(
                'assigned_to',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('assigned_to')
            ->pluck(
                'total',
                'assigned_to'
            );


        /*
        |--------------------------------------------------------------------------
        | Tickets resueltos durante el período
        |--------------------------------------------------------------------------
        */

        $resolved = $this->ticketQuery(
            'resolved_at'
        )
            ->whereNotNull('resolved_at')
            ->whereNotNull('assigned_to')
            ->select(
                'assigned_to',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('assigned_to')
            ->pluck(
                'total',
                'assigned_to'
            );


        /*
        |--------------------------------------------------------------------------
        | Tickets activos creados durante el período
        |--------------------------------------------------------------------------
        */

        $active = $this->ticketQuery(
            'created_at'
        )
            ->whereNotNull('assigned_to')
            ->whereIn(
                'status',
                $this->activeStatuses()
            )
            ->select(
                'assigned_to',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('assigned_to')
            ->pluck(
                'total',
                'assigned_to'
            );


        $ids = $assigned
            ->keys()
            ->merge(
                $resolved->keys()
            )
            ->merge(
                $active->keys()
            )
            ->unique()
            ->values();


        if ($ids->isEmpty()) {
            return [];
        }


        $users = User::query()
            ->whereIn(
                'id',
                $ids
            )
            ->pluck(
                'name',
                'id'
            );


        return $ids
            ->map(function (
                $userId
            ) use (
                $users,
                $assigned,
                $resolved,
                $active
            ) {

                return [
                    'id' =>
                        (int) $userId,

                    'name' =>
                        $users[$userId]
                        ?? 'Usuario eliminado',

                    'assigned' =>
                        (int) (
                            $assigned[$userId]
                            ?? 0
                        ),

                    'resolved' =>
                        (int) (
                            $resolved[$userId]
                            ?? 0
                        ),

                    'active' =>
                        (int) (
                            $active[$userId]
                            ?? 0
                        ),
                ];
            })
            ->sortByDesc('resolved')
            ->values()
            ->all();
    }


    private function periodLabel(): string
    {
        return match ($this->period) {
            '7' =>
                'Últimos 7 días',

            '30' =>
                'Últimos 30 días',

            '90' =>
                'Últimos 90 días',

            'year' =>
                'Este año',

            'all' =>
                'Todo el historial',

            default =>
                'Últimos 30 días',
        };
    }


    public function render()
    {
        $this->authorizeAdmin();


        /*
        |--------------------------------------------------------------------------
        | Métricas
        |--------------------------------------------------------------------------
        */

        $totalCreated = $this
            ->ticketQuery('created_at')
            ->count();


        $totalResolved = $this
            ->ticketQuery('resolved_at')
            ->whereNotNull('resolved_at')
            ->count();


        $totalActive = $this
            ->ticketQuery('created_at')
            ->whereIn(
                'status',
                $this->activeStatuses()
            )
            ->count();


        $criticalActive = $this
            ->ticketQuery('created_at')
            ->whereIn(
                'status',
                $this->activeStatuses()
            )
            ->where(
                'priority',
                TicketPriority::CRITICAL->value
            )
            ->count();


        $unassigned = $this
            ->ticketQuery('created_at')
            ->whereIn(
                'status',
                $this->activeStatuses()
            )
            ->whereNull('assigned_to')
            ->count();


        $averageResolutionMinutes =
            $this->averageResolutionMinutes();


        /*
        |--------------------------------------------------------------------------
        | Catálogos de filtros
        |--------------------------------------------------------------------------
        */

        $categories = Category::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);


        $technicians = User::query()
            ->where(
                'role',
                UserRole::TECHNICIAN->value
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);


        return view(
            'livewire.admin.reports',
            [
                'categories' =>
                    $categories,

                'technicians' =>
                    $technicians,

                'periodLabel' =>
                    $this->periodLabel(),

                'stats' => [
                    'created' =>
                        $totalCreated,

                    'resolved' =>
                        $totalResolved,

                    'active' =>
                        $totalActive,

                    'critical' =>
                        $criticalActive,

                    'unassigned' =>
                        $unassigned,

                    'average_resolution' =>
                        $this->formatDuration(
                            $averageResolutionMinutes
                        ),
                ],

                'statusDistribution' =>
                    $this->statusDistribution(
                        $totalCreated
                    ),

                'priorityDistribution' =>
                    $this->priorityDistribution(
                        $totalCreated
                    ),

                'categoryDistribution' =>
                    $this->categoryDistribution(
                        $totalCreated
                    ),

                'technicianPerformance' =>
                    $this->technicianPerformance(),
            ]
        );
    }
}