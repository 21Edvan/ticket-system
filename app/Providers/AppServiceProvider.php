<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use App\Observers\TicketAttachmentObserver;
use App\Observers\TicketCommentObserver;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Ticket::observe(TicketObserver::class);
        TicketComment::observe(
            TicketCommentObserver::class
        );

        TicketAttachment::observe(
            TicketAttachmentObserver::class
        );
        if (Schema::hasTable('company_settings')) {
            View::share(
                'company',
                CompanySetting::current()
            );
        }
    }
}
