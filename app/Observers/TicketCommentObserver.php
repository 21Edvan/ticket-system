<?php

namespace App\Observers;

use App\Models\TicketComment;
use Illuminate\Support\Facades\Storage;

class TicketCommentObserver
{
    public function deleted(TicketComment $comment): void
    {
        Storage::disk('attachments')
            ->deleteDirectory(
                "tickets/{$comment->ticket_id}/comments/{$comment->id}"
            );
    }
}