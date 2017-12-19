<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_notification_is_prepared_for_the_subscribed_user_when_thread_is_replied_by_someone_else()
    {
        $this->signIn();
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
        $user = $this->signIn();
        $thread = create('App\Thread')->subscribe();
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'FOOBAR'
        ]);

        $response = $this->getJson("profiles/{$user->name}/notifications")->json();
        $this->assertCount(1, $response);
        $this->assertEquals(null, $response[0]['read_at']);
    }


    /** @test */
    public function a_user_can_clear_notifications()
    {
        $user = $this->signIn();
        $thread = create('App\Thread')->subscribe();
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'FOOBAR'
        ]);

        $this->assertCount(1, $user->unreadNotifications);
        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
