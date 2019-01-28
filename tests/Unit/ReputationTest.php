<?php

namespace Tests\Feature;

use App\Helpers\Spam\Spam;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ReputationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_increment_reputation_when_user_make_a_thread()
    {
        $thread = create(Thread::class);
        $this->assertEquals(10, $thread->creator->reputation);
    }

    /** @test */
    public function it_increment_reputation_when_user_reply_in_thread()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Brand new reply'
        ]);

        $this->assertEquals(2, $reply->owner->reputation);
    }

    /** @test */
    public function it_increment_reputation_when_reply_marked_as_best()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Brand new reply'
        ]);

        $thread->markAsBest($reply);

        $this->assertEquals(52, $reply->owner->reputation);
    }
}
