<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThread extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_cannot_add_replies_to_locked_thread()
    {
        $this->signIn();
        $thread = create(Thread::class);

        $thread->lock();

        $this->post(route('replies.create', ['channel' => $thread->channel, 'thread' => $thread]), [
            'body' => 'lolololol',
        ])->assertStatus(422);
    }

    /** @test */
    public function non_administrator_cannot_lock_the_threads()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create(Thread::class);

        $this->post(route('lock-threads.store', $thread))
            ->assertStatus(403);

        $this->assertFalse($thread->locked);
    }

    /** @test */
    public function administrator_can_lock_the_threads()
    {
        $user = factory(User::class)->state('admin')->create();
        $this->signIn($user);
        $thread = create(Thread::class);

        $this->post(route('lock-threads.store', $thread))
            ->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked);
    }
}
