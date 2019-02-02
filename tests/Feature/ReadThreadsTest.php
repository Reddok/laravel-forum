<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }

    /** @test */
    public function user_can_see_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function user_can_see_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function user_can_filter_threads_by_channel()
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this->get(route('threads.channel', ['channel' => $channel->slug]))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function user_can_filter_unanswered_threads()
    {
        $threadWithReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithReplies->id], 10);

        $this->get(route('threads.index').'?unanswered=1')
            ->assertSee($this->thread->title)
            ->assertDontSee($threadWithReplies->title);
    }

    /** @test */
    public function user_can_filter_threads_by_username()
    {
        $anton = create(User::class, ['name' => 'Anton']);
        $threadByAnton = create(Thread::class, ['user_id' => $anton->id]);
        $threadNotByAnton = create(Thread::class);

        $this->get('threads?by=Anton')
            ->assertSee($threadByAnton->title)
            ->assertDontSee($threadNotByAnton->title);
    }

    /** @test */
    public function user_can_sort_threads_by_popularity()
    {
        $threadWithNoReplies = $this->thread;
        $threadWith2Replies = create(Thread::class);
        $threadWith3Replies = create(Thread::class);

        create(Reply::class, ['thread_id' => $threadWith2Replies->id], 2);
        create(Reply::class, ['thread_id' => $threadWith3Replies->id], 3);

        $threads = $this->get(route('threads.index').'?popular=1')->baseResponse->original->getData()['threads'];

        $this->assertEquals([3, 2, 0], $threads->pluck('replies_count')->toArray());
    }

    /** @test */
    public function user_can_request_all_replies_from_given_thread()
    {
        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id], 20);

        $response = $this->getJson(route('replies.index', [$thread->channel, $thread]))
            ->json();

        $this->assertCount(10, $response['data']);
        $this->assertEquals(20, $response['total']);
    }

    /** @test */
    public function thread_records_each_read()
    {
        $thread = create(Thread::class);
        $thread->visits->reset();

        $this->assertEquals(0, $thread->visits->count());

        $this->get($thread->path());

        $this->assertEquals(1, $thread->visits->count());

        $this->get($thread->path());

        $this->assertEquals(2, $thread->visits->count());
    }
}
