<?php

namespace App\Listeners;

use App\User;
use App\Notifications\YouAreMentioned;

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
