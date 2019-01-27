<?php

namespace App\Events;

use App\Reply;
use Illuminate\Foundation\Events\Dispatchable;

class ReplyPostedEvent
{
    use Dispatchable;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

}
