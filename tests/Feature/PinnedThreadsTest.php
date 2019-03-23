<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PinnedThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function administrator_can_pin_thread()
    {
        $this->signInAsAdmin();
        $thread = create(Thread::class);

        $this->post(route('pinned-threads.store', $thread));

        $this->assertTrue($thread->fresh()->pinned);
    }

    /** @test */
    public function administrator_can_unpin_thread()
    {
        $this->signInAsAdmin();
        $thread = create(Thread::class, ['pinned' => true]);

        $this->assertTrue($thread->pinned);

        $this->delete(route('pinned-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->pinned);
    }

    /** @test */
    public function pinned_threads_always_shows_first()
    {
        $this->signInAsAdmin();

        $threads = create(Thread::class, [], 3);
        $ids = $threads->pluck('id');

        $this->getJson(route('threads.index'))
            ->assertJson([
                'data' => [
                    ['id' => $ids[0]],
                    ['id' => $ids[1]],
                    ['id' => $ids[2]],
                ],
            ]);

        $this->post(route('pinned-threads.store', $threads->last()));

        $this->getJson(route('threads.index'))
            ->assertJson([
                'data' => [
                    ['id' => $ids[2]],
                    ['id' => $ids[0]],
                    ['id' => $ids[1]],
                ],
            ]);
    }
}
