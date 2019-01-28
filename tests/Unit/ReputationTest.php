<?php

namespace Tests\Feature;

use App\Helpers\Spam\Spam;
use App\Reputation;
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
        $this->assertEquals(Reputation::THREAD_PUBLISHED_AWARD, $thread->creator->reputation);
    }

    /** @test */
    public function it_increment_reputation_when_user_reply_in_thread()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Brand new reply'
        ]);

        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD, $reply->owner->reputation);
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

        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD + Reputation::BEST_REPLY_AWARD, $reply->owner->reputation);
    }
}
