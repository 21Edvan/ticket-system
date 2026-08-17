<?php

namespace App\Models;

use App\Enums\TicketHistoryAction;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketHistory extends Model
{
    protected $fillable = [
        'ticket_id',
        'actor_id',
        'action',
        'old_value',
        'new_value',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'action' => TicketHistoryAction::class,
            'metadata' => 'array',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function description(): string
    {
        return match ($this->action) {
            TicketHistoryAction::CREATED =>
                'Ticket creado',

            TicketHistoryAction::TECHNICIAN_ASSIGNED =>
                'Técnico asignado: '
                .($this->metadata['new_technician_name'] ?? 'Desconocido'),

            TicketHistoryAction::TECHNICIAN_REASSIGNED =>
                'Técnico cambiado de '
                .($this->metadata['old_technician_name'] ?? 'Desconocido')
                .' a '
                .($this->metadata['new_technician_name'] ?? 'Desconocido'),

            TicketHistoryAction::TECHNICIAN_UNASSIGNED =>
                'Técnico removido: '
                .($this->metadata['old_technician_name'] ?? 'Desconocido'),

            TicketHistoryAction::STATUS_CHANGED =>
                'Estado cambiado de '
                .$this->statusLabel($this->old_value)
                .' a '
                .$this->statusLabel($this->new_value),
        };
    }

    private function statusLabel(?string $status): string
    {
        if (! $status) {
            return 'Desconocido';
        }

        return TicketStatus::tryFrom($status)?->label() ?? $status;
    }
}