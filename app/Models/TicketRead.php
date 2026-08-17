<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketRead extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'last_read_comment_id',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastReadComment(): BelongsTo
    {
        return $this->belongsTo(
            TicketComment::class,
            'last_read_comment_id'
        );
    }
}