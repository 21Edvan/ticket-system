<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $kind,
        public string $title,
        public string $message,
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'kind' => $this->kind,

            'ticket_id' => $this->ticket->id,

            'ticket_number' => $this->ticket->ticket_number,

            'title' => $this->title,

            'message' => $this->message,
        ];
    }
}