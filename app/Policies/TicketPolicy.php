<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id === $user->id
            || $ticket->assigned_to === $user->id;
    }
    public function updateStatus(User $user, Ticket $ticket): bool
    {
        return $user->isTechnician()
            && $ticket->assigned_to === $user->id;
    }
}