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
    public function a_guest_user_may_not_see_create_thread_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirectedToRoute('login');
    }

    /** @test */
    public function a_guest_user_may_not_create_a_thread()
    {
        $this->expectException(AuthenticationException::class);
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
    }
    
    /** @test */
    public function a_logged_in_user_can_create_a_thread()
    {
        $user = $this->signIn();
        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->see($user->name)
            ->see($thread->body)
            ->see($thread->title);
    }
}
