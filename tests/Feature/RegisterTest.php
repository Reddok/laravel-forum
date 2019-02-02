<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Mail\PleaseConfirmEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_sends_email_when_registration_completes()
    {
        Mail::fake();

        event(new Registered(create(User::class)));

        Mail::assertQueued(PleaseConfirmEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_his_email()
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'John',
            'email' => 'johndoe@email.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $user = User::where(['name' => 'John'])->first();

        $this->assertEquals(false, $user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('confirmation.index', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads.index'));

        $this->assertTrue($user->fresh()->confirmed);
        $this->assertNull($user->fresh()->confirmation_token);
    }

    /** @test */
    public function invalid_token_not_confirm_a_user()
    {
        $user = create(User::class, ['confirmed' => false]);

        $this->get(route('confirmation.index', ['token' => 'Invalid Token']))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', '{"message":"Invalid Token!","level":"danger"}');

        $this->assertFalse($user->fresh()->confirmed);
    }
}
