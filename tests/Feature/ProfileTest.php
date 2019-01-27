<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = create(User::class);
    }

    /** @test */
    public function user_has_profile_page()
    {
        $this->get(route('profiles.index', $this->user->name))
            ->assertSee($this->user->name);
    }

    /** @test */
    public function profile_has_threads_created_by_user()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->get(route('profiles.index', auth()->user()->name))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function profile_has_feed()
    {
        $this->signIn();
        create(Thread::class, ['user_id' => auth()->id()], 2);

        auth()->user()->activities()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $this->assertContains(Carbon::now()->format('Y-m-d'), Activity::feed(auth()->user())->keys());
        $this->assertContains(Carbon::now()->subWeek()->format('Y-m-d'), Activity::feed(auth()->user())->keys());
    }
}
