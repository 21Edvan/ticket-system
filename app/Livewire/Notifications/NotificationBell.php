<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public function markAllAsRead(): void
    {
        Auth::user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);
    }

    public function openNotification(string $notificationId)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        $ticketId = $notification->data['ticket_id'] ?? null;

        if (! $ticketId) {
            return $this->redirect(
                route('dashboard')
            );
        }

        return $this->redirect(
            route('tickets.show', $ticketId)
        );
    }

    public function render()
    {
        $user = Auth::user();

        return view(
            'livewire.notifications.notification-bell',
            [
                'notifications' => $user
                    ->notifications()
                    ->limit(8)
                    ->get(),

                'unreadCount' => $user
                    ->unreadNotifications()
                    ->count(),
            ]
        );
    }
}