<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateForumTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function authentificated_user_can_see_reply_after_creating_it()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class);

        $this->post($thread->path('replies'), $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }

    /** @test */
    public function non_authentificated_user_cannot_add_reply()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);
        $this->post($thread->path('replies'), [])
            ->assertRedirect(route('login'));
    }
    
    /** @test */
    public function reply_requires_body()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->postJson($thread->path('replies'), $reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function not_authorized_users_cannot_delete_a_reply()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->delete(route('replies.delete', $reply))
            ->assertRedirect((route('login')));

        $this->signIn();

        $this->delete(route('replies.delete', $reply))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_a_reply()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->delete(route('replies.delete', $reply))
            ->assertStatus(200);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function authorized_users_can_update_reply()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);
        $body = 'You have been changed, fool';

        $this->patch(route('replies.update', $reply), compact('body'));
        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $body
        ]);
    }

    /** @test */
    public function not_authorized_users_cannot_update_reply()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);
        $body = 'You have been changed, fool';
        $this->patch(route('replies.update', $reply), compact('body'))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('replies.update', $reply), compact('body'))
            ->assertStatus(403);
    }

    /** @test */
    public function not_allow_user_create_a_spam_reply()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $thread = create(Thread::class);

//        $this->expectException(\Exception::class);

        $this->postJson(route('replies.create', ['channel' => $thread->channel, 'thread' => $thread]), [
            'body' => 'Yahoo Customer Support'
        ])->assertStatus(422);
    }

    /** @test */
    public function user_cannot_create_reply_more_than_once_per_minute()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $thread = create(Thread::class);
        $reply = ['body' => 'Some reply body'];

        $this->postJson(route('replies.create', ['channel' => $thread->channel, 'thread' => $thread]), $reply)
            ->assertStatus(201);

        sleep(1);

        $this->postJson(route('replies.create', ['channel' => $thread->channel, 'thread' => $thread]), $reply)
            ->assertStatus(429);
    }

}
