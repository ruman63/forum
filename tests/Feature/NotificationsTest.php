<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->signIn();
    }
    
    /** @test */
    public function a_notification_is_prepared_for_the_subscribed_user_when_thread_is_replied_by_someone_else()
    {
        $thread = create('App\Thread')->subscribe();

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => "FooBar"
        ]);

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => "FooBar Again"
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
    /** @test */
    public function a_user_can_fetch_unread_notifications()
    {
        create(DatabaseNotification::class);

        $this->assertCount(
            1,
            $this->getJson("profiles/" . auth()->user()->name . "/notifications")->json()
        );
    }


    /** @test */
    public function a_user_can_clear_notifications()
    {
        create(DatabaseNotification::class);
        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);
    
            $this->delete("profiles/" . $user->name . "/notifications/" . $user->unreadNotifications->first()->id);
    
            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
