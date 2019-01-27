<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_fetch_his_last_reply()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->assertEquals($reply->id, auth()->user()->lastReply->id);
    }
}
