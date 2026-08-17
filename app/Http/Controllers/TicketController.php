<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function show(Ticket $ticket): View
    {
        $ticket->load([
            'category',
            'user',
            'technician',
        ]);

        return view('tickets.show', compact('ticket'));
    }
}