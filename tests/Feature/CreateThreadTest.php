<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_guest_user_may_not_create_a_thread()
    {
        $this->expectException(AuthenticationException::class);
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());
    }
    
    /** @test */
    public function a_logged_in_user_can_create_a_thread()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());

        $this->get('/threads/1')
            ->see($thread->body)
            ->see($thread->title);
    }
}
