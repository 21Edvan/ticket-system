<?php

namespace App\Observers;

use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentObserver
{
    public function deleted(TicketAttachment $attachment): void
    {
        if ($attachment->path) {
            Storage::disk('attachments')
                ->delete($attachment->path);
        }
    }
}