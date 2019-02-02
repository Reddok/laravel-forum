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

    /** @test */
    public function it_increment_reputation_when_reply_favorited()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Brand new reply'
        ]);

        $this->post(route('replies.favorite', compact('reply')));

        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD + Reputation::REPLY_FAVORITED_AWARD, $reply->fresh()->owner->reputation);
    }

    /** @test */
    public function it_rewoke_reputation_when_thread_deleted()
    {
        $user = create(User::class);
        $this->signIn($user);

        $thread = create(Thread::class, ['user_id' => $user->id]);
        $this->assertEquals(Reputation::THREAD_PUBLISHED_AWARD, $user->fresh()->reputation);

        $this->delete($thread->path());
        $this->assertEquals(0, $user->reputation);
    }

    /** @test */
    public function it_rewoke_reputation_when_reply_deleted()
    {
        $user = create(User::class);
        $this->signIn($user);

        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => $user->id,
            'body' => 'Brand new reply'
        ]);

        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD, $user->fresh()->reputation);

        $this->delete(route('replies.delete', compact('reply')));
        $this->assertEquals(0, $user->reputation);
    }

    /** @test */
    public function it_rewoke_reputation_when_reply_unmarked_as_best()
    {
        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Brand new reply'
        ]);

        $thread->markAsBest($reply);
        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD + Reputation::BEST_REPLY_AWARD, $reply->owner->reputation);
        $thread->refresh();

        $anotherReply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Even better reply'
        ]);

        $thread->markAsBest($anotherReply);
        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD, $reply->fresh()->owner->reputation);
    }

    /** @test */
    public function it_rewoke_reputation_when_reply_unfavorited()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Brand new reply'
        ]);

        $this->post(route('replies.favorite', compact('reply')));

        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD + Reputation::REPLY_FAVORITED_AWARD, $reply->fresh()->owner->reputation);

        $this->delete(route('replies.unfavorite', compact('reply')));
        $this->assertEquals(Reputation::REPLY_IN_POST_AWARD, $reply->fresh()->owner->reputation);
    }
}
