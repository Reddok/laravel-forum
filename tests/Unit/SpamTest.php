<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\Spam\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function throws_an_exception_when_forbid_keywords_has_been_used_in_text()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent user string'));
        $this->expectException(\Exception::class);

        $spam->detect('There is been a while the yaHoo customer support walking to the road');
    }

    /** @test */
    public function throws_an_exceptions_when_user_hold_key_in_reply()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent user string'));
        $this->expectException(\Exception::class);

        $spam->detect('There is been aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
    }
}
