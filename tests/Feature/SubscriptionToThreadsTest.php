<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscriptionToThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        $this->assertDatabaseHas('thread_subscriptions', [
            'user_id' => auth()->id(),
            'thread_id' => $thread->id
        ]);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertDatabaseMissing('thread_subscriptions', [
            'user_id' => auth()->id(),
            'thread_id' => $thread->id
        ]);
    }
}
