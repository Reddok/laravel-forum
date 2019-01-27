<?php

namespace App\Listeners;

use App\Notifications\YouAreMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentioned
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        User::whereIn('name', $event->reply->detectMentioned())
            ->get()
            ->each->notify(new YouAreMentioned($event->reply));
    }
}
