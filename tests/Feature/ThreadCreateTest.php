<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadCreateTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function guest_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));

        $this->post(route('threads.store'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function non_confirmed_users_cannot_create_thread()
    {
        $this->publishThread([], create(User::class, ['confirmed' => false]))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', '{"message":"You need to confirm email before take an action!","level":"danger"}');
    }

    /** @test */
    public function authentificated_user_can_create_threads()
    {
        $response = $this->publishThread(['title' => 'Some Title', 'body' => 'Some Body']);

        $this->get($response->headers->get('Location'))
            ->assertSee('Some Title')
            ->assertSee('Some Body');
    }

    /** @test */
    public function thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors();
    }

    /** @test */
    public function thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors();
    }

    /** @test */
    public function thread_requires_a_valid_channel()
    {
        $channel = create(Channel::class);

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors();

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors();

        $this->publishThread(['channel_id' => $channel->id])
            ->assertSessionHasNoErrors();
    }

    /** @test */
    public function thread_can_be_deleted()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_type' => get_class($thread),
            'subject_id' => $thread->id,
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_type' => get_class($reply),
            'subject_id' => $reply->id,
        ]);
    }

    /** @test */
    public function not_authentificated_users_can_not_delete_thread()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);
    }

    /** @test */
    public function thread_must_have_an_unique_slug()
    {
        $this->signIn();
        $thread = create(Thread::class, ['title' => 'Foo Bar']);

        $this->assertEquals('foo-bar', $thread->slug);

        $thread = $this->postJson(route('threads.store'), $thread->toArray() + ['g-recaptcha-response' => 'test'])->json();

        $this->assertEquals('foo-bar-'.$thread['id'], $thread['slug']);
    }

    /** @test */
    public function thread_must_properly_increment_slug()
    {
        $this->signIn();
        $thread = create(Thread::class, ['title' => 'Foo Bar 23']);

        $this->post(route('threads.store'), $thread->toArray() + ['g-recaptcha-response' => 'test']);

        $this->assertTrue(Thread::whereSlug('foo-bar-23-2')->exists());
    }

    /** @test */
    public function thread_must_pass_captcha_validation()
    {
        unset(app()[Recaptcha::class]);
        $this->publishThread()
            ->assertSessionHasErrors();
    }

    protected function publishThread(array $overrides = [], User $user = null)
    {
        $this->signIn($user)
            ->withExceptionHandling();

        $thread = make(Thread::class, $overrides);

        return $this->post(route('threads.store'), $thread->toArray() + ['g-recaptcha-response' => 'test']);
    }
}
