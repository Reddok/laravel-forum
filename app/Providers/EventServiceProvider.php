<?php

namespace App\Providers;

use App\Events\ReplyPostedEvent;
use App\Listeners\NotifyMentioned;
use App\Listeners\NotifySubscribers;
use App\Listeners\SendEmailConfirmationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ReplyPostedEvent::class => [
            NotifyMentioned::class,
            NotifySubscribers::class
        ],
        Registered::class => [
            SendEmailConfirmationRequest::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
