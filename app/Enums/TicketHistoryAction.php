<?php

namespace App\Enums;

enum TicketHistoryAction: string
{
    case CREATED = 'created';
    case TECHNICIAN_ASSIGNED = 'technician_assigned';
    case TECHNICIAN_REASSIGNED = 'technician_reassigned';
    case TECHNICIAN_UNASSIGNED = 'technician_unassigned';
    case STATUS_CHANGED = 'status_changed';
}