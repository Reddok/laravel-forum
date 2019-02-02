<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }

    /** @test */
    public function it_can_have_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function it_can_have_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    public function it_can_create_reply()
    {
        $this->thread->addReply([
            'body' => 'foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function it_can_have_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function it_have_valid_path()
    {
        $this->assertEquals('/threads/'.$this->thread->channel->slug.'/'.$this->thread->slug, $this->thread->path());
    }

    /** @test */
    public function user_can_subscribe_on_thread()
    {
        $user = create(User::class);
        $this->thread->subscribe($user->id);
        $this->assertCount(1, $this->thread->subscriptions()->where(['user_id' => $user->id])->get());
    }

    /** @test */
    public function user_can_unsubscribe_from_thread()
    {
        $user = create(User::class);
        $this->thread->subscribe($user->id);
        $this->thread->unsubscribe($user->id);

        $this->assertCount(0, $this->thread->subscriptions()->where(['user_id' => $user->id])->get());
    }

    /** @test */
    public function user_can_see_updated_threads()
    {
        $this->signIn();
        $user = auth()->user();

        $this->assertTrue($this->thread->hasUpdatesFor($user));

        $user->read($this->thread);

        $this->assertFalse($this->thread->hasUpdatesFor($user));

        sleep(1);

        $this->thread->addReply([
            'body' => 'test reply',
            'user_id' => create(User::class)->id,
        ]);

        $this->assertTrue($this->thread->fresh()->hasUpdatesFor($user));
    }

    /** @test */
    public function it_can_be_locked()
    {
        $this->assertFalse($this->thread->locked);
        $this->thread->lock();
        $this->assertTrue($this->thread->locked);
    }
}
