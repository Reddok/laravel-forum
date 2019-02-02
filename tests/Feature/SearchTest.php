<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_search_among_the_threads()
    {
        config()->set('scout.driver', 'algolia');
        $search = 'Foobar';
        create(Thread::class, [], 2);
        create(Thread::class, ['body' => "Thread with {$search} term"], 2);

        do {
            sleep(1);
            $response = $this->getJson(route('threads.search', ['q' => $search]))->json()['data'];
        } while (count($response) < 2);

        $this->assertCount(2, $response);

        Thread::latest(4)->unsearchable();
    }
}
