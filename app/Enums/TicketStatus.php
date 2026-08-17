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
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::OPEN => [
                self::ASSIGNED,
            ],

            self::ASSIGNED => [
                self::IN_PROGRESS,
            ],

            self::IN_PROGRESS => [
                self::WAITING,
                self::RESOLVED,
            ],

            self::WAITING => [
                self::IN_PROGRESS,
                self::RESOLVED,
            ],

            self::RESOLVED => [
                self::IN_PROGRESS,
                self::CLOSED,
            ],

            self::CLOSED => [],
        };
    }
}