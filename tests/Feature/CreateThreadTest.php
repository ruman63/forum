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
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirectedToRoute('login');

        $this->post('/threads')
            ->assertRedirectedToRoute('login');
    }
    
    /** @test */
    public function a_logged_in_user_can_create_a_thread()
    {
        $user = $this->signIn();
        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray())
            ->assertRedirectedTo("/threads/{$thread->channel->slug}/1");

        $this->get("/threads/{$thread->channel->slug}/1")
            ->see($user->name)
            ->see($thread->body)
            ->see($thread->title);
    }
}
