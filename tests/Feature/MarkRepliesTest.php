<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MarkRepliesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_mark_reply_as_best()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->post(route('best-replies.store', $replies[1]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_creator_can_mark_reply_as_best()
    {
        $this->signIn()
            ->withExceptionHandling();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->signIn(create(User::class));

        $this->post(route('best-replies.store', $replies[1]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function if_reply_is_deleted_thread_properly_updated_to_reflect_that()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id, 'user_id' => auth()->id()]);

        $this->post(route('best-replies.store', $reply));
        $this->assertTrue($reply->fresh()->isBest());
        $this->assertEquals($thread->fresh()->best_reply_id, $reply->id);

        $response = $this->delete(route('replies.delete', $reply));

//        $this->assertNull($thread->fresh()->best_reply_id);
    }
}
