<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use App\Enums\TicketStatus;

class TicketPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin() && $ability !== 'comment') {
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

    public function comment(User $user, Ticket $ticket): bool
    {
        if ($ticket->status === TicketStatus::CLOSED) {
            return false;
        }

        return $user->isAdmin()
            || $ticket->user_id === $user->id
            || (
                $user->isTechnician()
                && $ticket->assigned_to === $user->id
            );
    }

}