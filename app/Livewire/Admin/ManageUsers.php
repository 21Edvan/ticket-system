<?php

namespace App\Livewire\Admin;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';

    public ?int $editingUserId = null;
    public string $role = '';

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function editRole(int $userId): void
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            $this->addError(
                'role',
                'No puedes modificar tu propio rol.'
            );

            return;
        }

        $this->resetErrorBag();

        $this->editingUserId = $user->id;
        $this->role = $user->role->value;
    }

    public function cancelEdit(): void
    {
        $this->reset([
            'editingUserId',
            'role',
        ]);

        $this->resetErrorBag();
    }

    public function updateRole(): void
    {
        $this->authorizeAdmin();

        $validated = $this->validate([
            'editingUserId' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'role' => [
                'required',
                Rule::enum(UserRole::class),
            ],
        ]);

        $user = User::findOrFail(
            $validated['editingUserId']
        );

        if ($user->id === Auth::id()) {
            $this->addError(
                'role',
                'No puedes modificar tu propio rol.'
            );

            return;
        }

        $newRole = UserRole::from($validated['role']);

        /*
         * Si un técnico tiene tickets activos,
         * no permitimos convertirlo en usuario.
         */
        if (
            $user->isTechnician()
            && $newRole !== UserRole::TECHNICIAN
        ) {
            $activeStatuses = [
                TicketStatus::OPEN->value,
                TicketStatus::ASSIGNED->value,
                TicketStatus::IN_PROGRESS->value,
                TicketStatus::WAITING->value,
            ];

            $hasActiveTickets = $user
                ->assignedTickets()
                ->whereIn('status', $activeStatuses)
                ->exists();

            if ($hasActiveTickets) {
                $this->addError(
                    'role',
                    'Este técnico tiene tickets activos. Reasígnalos antes de cambiar su rol.'
                );

                return;
            }
        }

        $user->role = $newRole;
        $user->save();

        session()->flash(
            'success',
            'Rol actualizado correctamente.'
        );

        $this->cancelEdit();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'roleFilter',
        ]);

        $this->resetPage();
    }

    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->isAdmin(),
            403
        );
    }

    public function render()
    {
        $users = User::query()
            ->withCount([
                'tickets',
                'assignedTickets',
            ])
            ->when(
                $this->search,
                function ($query) {
                    $query->where(function ($query) {
                        $query
                            ->where(
                                'name',
                                'like',
                                '%'.$this->search.'%'
                            )
                            ->orWhere(
                                'email',
                                'like',
                                '%'.$this->search.'%'
                            );
                    });
                }
            )
            ->when(
                $this->roleFilter,
                fn ($query) => $query->where(
                    'role',
                    $this->roleFilter
                )
            )
            ->orderBy('name')
            ->paginate(12);

        $stats = [
            'total' => User::count(),

            'admins' => User::where(
                'role',
                UserRole::ADMIN->value
            )->count(),

            'technicians' => User::where(
                'role',
                UserRole::TECHNICIAN->value
            )->count(),

            'users' => User::where(
                'role',
                UserRole::USER->value
            )->count(),
        ];

        return view(
            'livewire.admin.manage-users',
            [
                'users' => $users,
                'roles' => UserRole::cases(),
                'stats' => $stats,
            ]
        );
    }
}