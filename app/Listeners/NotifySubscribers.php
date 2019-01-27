<?php

namespace App\Listeners;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $reply = $event->reply;
        $thread = $reply->thread;

        $thread->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each->notify(new ThreadWasUpdated($thread, $reply));
    }
}
