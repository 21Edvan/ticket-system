<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'ticket_comment_id',
        'uploaded_by',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(
            TicketComment::class,
            'ticket_comment_id'
        );
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function isImage(): bool
    {
        return str_starts_with(
            $this->mime_type ?? '',
            'image/'
        );
    }

    public function formattedSize(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1024 * 1024) {
            return round(
                $bytes / 1024 / 1024,
                1
            ).' MB';
        }

        if ($bytes >= 1024) {
            return round(
                $bytes / 1024,
                1
            ).' KB';
        }

        return $bytes.' B';
    }
}