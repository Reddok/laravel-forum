<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubsribeToThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $this->post($thread->path().'/subscriptions');

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'test reply',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();
        $this->delete($thread->path().'/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
