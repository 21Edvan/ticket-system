<?php

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function show(TicketAttachment $attachment)
    {
        $attachment->loadMissing('ticket');

        Gate::authorize(
            'view',
            $attachment->ticket
        );

        $disk = Storage::disk('attachments');

        abort_unless(
            $disk->exists($attachment->path),
            404
        );

        return $disk->response(
            $attachment->path,
            $attachment->original_name,
            [
                'Content-Type' => $attachment->mime_type
                    ?? 'application/octet-stream',
            ],
            'inline'
        );
    }

    public function download(TicketAttachment $attachment)
    {
        $attachment->loadMissing('ticket');

        Gate::authorize(
            'view',
            $attachment->ticket
        );

        $disk = Storage::disk('attachments');

        abort_unless(
            $disk->exists($attachment->path),
            404
        );

        return $disk->response(
            $attachment->path,
            $attachment->original_name,
            [
                'Content-Type' => $attachment->mime_type
                    ?? 'application/octet-stream',
            ],
            'attachment'
        );
    }
}