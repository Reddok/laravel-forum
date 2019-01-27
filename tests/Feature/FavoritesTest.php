<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class FavoritesTest extends TestCase
{
    protected $reply;

    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        $this->reply = create(Reply::class);
    }

    /** @test */
    public function authentificated_user_can_favorites_reply()
    {
        $this->signIn();
        $this->post(route('replies.favorite', ['reply' => $this->reply->id]));

        $this->assertCount(1, $this->reply->favorites);
    }

    /** @test */
    public function guests_dont_allowed_to_favorite_replies()
    {
        $this->withExceptionHandling();

        $this->post(route('replies.favorite', ['reply' => $this->reply->id]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_favorite_reply_only_one_time()
    {
        $this->signIn();
        $this->post(route('replies.favorite', ['reply' => $this->reply->id]));
        $this->post(route('replies.favorite', ['reply' => $this->reply->id]));

        $this->assertCount(1, $this->reply->favorites);
    }

    /** @test */
    public function user_can_unfavorite_reply()
    {
        $this->signIn();

        $this->reply->favorite();

        $this->delete(route('replies.unfavorite', $this->reply));
        $this->assertEquals(0, $this->reply->favoritesCount);
    }
}
