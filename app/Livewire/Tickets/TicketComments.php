<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Models\TicketRead;

class TicketComments extends Component
{
    #[Locked]
    public int $ticketId;

    public int $unreadCount = 0;

    public string $body = '';

    public int $lastCommentId = 0;

    public function mount(Ticket $ticket): void
    {
        Gate::authorize('view', $ticket);

        $this->ticketId = $ticket->id;

        $this->lastCommentId = (int) (
            $ticket->comments()->max('id') ?? 0
        );

        $this->updateUnreadCount();
    }

    public function addComment(): void
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        Gate::authorize('comment', $ticket);

        $validated = $this->validate([
            'body' => [
                'required',
                'string',
                'min:2',
                'max:5000',
            ],
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        $this->lastCommentId = $comment->id;

        $this->reset('body');

        $this->markAsRead();

        $this->dispatch('comments-updated');
    }

    public function refreshComments(): void
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        Gate::authorize('view', $ticket);

        $latestCommentId = (int) (
            $ticket->comments()->max('id') ?? 0
        );

        if ($latestCommentId > $this->lastCommentId) {

            $this->lastCommentId = $latestCommentId;

            $this->updateUnreadCount();

            $this->dispatch('comments-updated');
        }
    }

    public function render()
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        Gate::authorize('view', $ticket);

        return view('livewire.tickets.ticket-comments', [
            'ticket' => $ticket,

            'comments' => $ticket->comments()
                ->with('user')
                ->oldest()
                ->get(),
        ]);
    }

    private function updateUnreadCount(): void
    {
        $lastReadCommentId = (int) (
            TicketRead::query()
                ->where('ticket_id', $this->ticketId)
                ->where('user_id', Auth::id())
                ->value('last_read_comment_id')
                ?? 0
        );

        $this->unreadCount = Ticket::findOrFail($this->ticketId)
            ->comments()
            ->where('id', '>', $lastReadCommentId)
            ->where('user_id', '!=', Auth::id())
            ->count();
    }

    public function markAsRead(): void
    {
        $ticket = Ticket::findOrFail($this->ticketId);

        Gate::authorize('view', $ticket);

        $latestCommentId = $ticket->comments()
            ->max('id');

        TicketRead::updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
            ],
            [
                'last_read_comment_id' => $latestCommentId,
            ]
        );

        $this->unreadCount = 0;
    }
}