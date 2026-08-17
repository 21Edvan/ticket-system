<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case ASSIGNED = 'assigned';
    case IN_PROGRESS = 'in_progress';
    case WAITING = 'waiting';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Abierto',
            self::ASSIGNED => 'Asignado',
            self::IN_PROGRESS => 'En proceso',
            self::WAITING => 'En espera',
            self::RESOLVED => 'Resuelto',
            self::CLOSED => 'Cerrado',
        };
    }
}