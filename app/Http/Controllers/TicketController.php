<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function show(Ticket $ticket)
    {
        $ticket->load([
            'category',
            'user',
            'technician',
            'directAttachments.uploader',
        ]);

        return view(
            'tickets.show',
            compact('ticket')
        );
    }
}