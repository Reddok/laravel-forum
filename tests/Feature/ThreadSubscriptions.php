<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadSubscriptions extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_subscribe_on_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->post($thread->path().'/subscriptions');

        $reply = make(Reply::class, [
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
        ]);
        $thread->addReply($reply->toArray());
        $user = auth()->user();

        $this->assertCount(1, $user->notifications);
    }
}
