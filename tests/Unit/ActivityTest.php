<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_thread_created()
    {
        $this->signIn();
        $userId = auth()->id();

        $thread = create(Thread::class, ['user_id' => $userId]);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => $userId,
            'subject_id' => $thread->id,
            'subject_type' => Thread::class,
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_reply_created()
    {
        $this->signIn();
        $userId = auth()->id();

        create(Reply::class, ['user_id' => $userId]);

        $this->assertEquals(2, Activity::count());
    }
}
