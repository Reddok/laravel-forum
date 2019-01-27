<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;


class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_user_can_fetch_his_notifications()
    {
        create(DatabaseNotification::class);
        $response = $this->getJson(route('notifications.index', auth()->user()))->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_notifications_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {


        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some test'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'some test'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);

    }

    /** @test */
    public function a_user_can_read_notifications()
    {
        create(DatabaseNotification::class);

        $user = auth()->user();
        $this->assertCount(1, $user->unreadNotifications);
        $notification = $user->unreadNotifications()->first();

        $this->delete(route('notifications.delete', ['user' => $user, 'notification' => $notification]));

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
