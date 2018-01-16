<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_admin_user_cannot_lock_a_thread()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('lock-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function an_admin_user_can_lock_a_thread()
    {
        $this->signIn(factory('App\User')->states('admin')->create());

        $thread = create('App\Thread');

        $this->post(route('lock-threads.store', $thread))->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function non_admin_user_cannot_unlock_a_thread()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread', ['locked' => true]);

        $this->delete(route('lock-threads.destroy', $thread))->assertStatus(403);

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function an_admin_user_can_unlock_a_thread()
    {
        $this->signIn(factory('App\User')->states('admin')->create());

        $thread = create('App\Thread', ['locked' => true]);

        $this->delete(route('lock-threads.destroy', $thread))->assertStatus(200);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function once_locked_a_thread_cannot_have_new_replies()
    {
        $this->signIn();
        $thread = create('App\Thread', ['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Some Reply!',
        ])->assertStatus(422);
    }
}
