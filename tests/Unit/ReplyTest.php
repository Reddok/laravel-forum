<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    protected $reply;

    public function setUp()
    {
        parent::setUp();
        $this->reply = create(Reply::class);
    }

    /** @test */
    public function it_can_have_owner()
    {
        $this->assertInstanceOf(User::class, $this->reply->owner);
    }

    /** @test */
    public function it_can_show_if_it_just_published()
    {
        $this->assertTrue($this->reply->justPublished());
        $this->reply->created_at = $this->reply->created_at->subMinute();
        $this->assertFalse($this->reply->justPublished());
    }

    /** @test */
    public function it_can_detect_mentioned_in_body()
    {
        $reply = create(Reply::class, ['body' => '@JaneDoe says hello to @JohnDoe']);
        $this->assertEquals($reply->detectMentioned(), ['JaneDoe', 'JohnDoe']);
    }

    /** @test */
    public function it_can_be_marked_as_best()
    {
        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->assertFalse($reply->isBest());

        $thread->markAsBest($reply);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
