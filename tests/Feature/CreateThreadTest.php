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
        $this->withExceptionHandling();
        $user = $this->signIn();
        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray())
            ->assertRedirectedTo("/threads/{$thread->channel->slug}/1");

        $this->get($this->response->headers->get('Location'))
            ->see($user->name)
            ->see($thread->body)
            ->see($thread->title);
    }

    /** @test */
    public function a_thread_requires_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel_id()
    {
        factory('App\Channel', 2)->create();
        
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
        ->assertSessionHasErrors('channel_id');
    }

    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
