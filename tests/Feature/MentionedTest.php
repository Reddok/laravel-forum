<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionedTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function if_user_mentioned_in_reply_he_receive_notificaion()
    {
        $john = create(User::class);
        $jane = create(User::class, [
            'name' => 'JaneDoe'
        ]);
        $thread = create(Thread::class);

        $this->signIn($john);

        $this->postJson(route('replies.create', ['channel' => $thread->channel, 'thread' => $thread]), [
            'body' => 'Hello, @JaneDoe'
        ])
            ->assertStatus(201);

        $this->assertCount(1, $jane->notifications);

    }

    /** @test */
    public function it_create_links_from_mentioned_users()
    {
        $jane = create(User::class, [
            'name' => 'JaneDoe'
        ]);
        $reply = create(Reply::class, [
            'body' => 'Hello, @JaneDoe'
        ]);

        $this->assertEquals('Hello, <a href="' . route('profiles.index', $jane) . '">@JaneDoe</a>', $reply->body);
    }

    /** @test */
    public function it_can_return_autocomplete_for_mentioned_users()
    {
        create(User::class, ['name' => 'JohnDoe']);
        create(User::class, ['name' => 'JohnDoe2']);
        create(User::class, ['name' => 'JaneDoe']);

        $response = $this->json('GET', route('api.users.index'), ['query' => 'john']);
        $this->assertCount(2, $response->json());
    }
}
