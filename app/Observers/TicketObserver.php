<?php

namespace App\Observers;

use App\Enums\TicketHistoryAction;
use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketObserver
{

    public function deleted(Ticket $ticket): void
    {
        Storage::disk('attachments')
            ->deleteDirectory("tickets/{$ticket->id}");
    }

    public function created(Ticket $ticket): void
    {
        TicketHistory::create([
            'ticket_id' => $ticket->id,

            'actor_id' => Auth::id() ?? $ticket->user_id,

            'action' => TicketHistoryAction::CREATED,
        ]);
    }

    public function updated(Ticket $ticket): void
    {
        $actorId = Auth::id();

        $this->recordTechnicianChange($ticket, $actorId);

        $this->recordStatusChange($ticket, $actorId);
    }

    private function recordTechnicianChange(
        Ticket $ticket,
        ?int $actorId
    ): void {
        if (! $ticket->wasChanged('assigned_to')) {
            return;
        }

        $oldTechnicianId = $ticket->getRawOriginal('assigned_to');

        $oldTechnicianId = $oldTechnicianId !== null
            ? (int) $oldTechnicianId
            : null;

        $newTechnicianId = $ticket->assigned_to !== null
            ? (int) $ticket->assigned_to
            : null;

        $action = match (true) {
            $oldTechnicianId === null && $newTechnicianId !== null
                => TicketHistoryAction::TECHNICIAN_ASSIGNED,

            $oldTechnicianId !== null && $newTechnicianId === null
                => TicketHistoryAction::TECHNICIAN_UNASSIGNED,

            default
                => TicketHistoryAction::TECHNICIAN_REASSIGNED,
        };

        TicketHistory::create([
            'ticket_id' => $ticket->id,

            'actor_id' => $actorId,

            'action' => $action,

            'old_value' => $oldTechnicianId
                ? (string) $oldTechnicianId
                : null,

            'new_value' => $newTechnicianId
                ? (string) $newTechnicianId
                : null,

            'metadata' => [
                'old_technician_name' => $oldTechnicianId
                    ? User::find($oldTechnicianId)?->name
                    : null,

                'new_technician_name' => $newTechnicianId
                    ? User::find($newTechnicianId)?->name
                    : null,
            ],
        ]);
    }

    private function recordStatusChange(
        Ticket $ticket,
        ?int $actorId
    ): void {
        if (! $ticket->wasChanged('status')) {
            return;
        }

        $oldStatus = $ticket->getRawOriginal('status');

        $newStatus = $ticket->status->value;

        if ($oldStatus === $newStatus) {
            return;
        }

        TicketHistory::create([
            'ticket_id' => $ticket->id,

            'actor_id' => $actorId,

            'action' => TicketHistoryAction::STATUS_CHANGED,

            'old_value' => $oldStatus,

            'new_value' => $newStatus,
        ]);
    }
}