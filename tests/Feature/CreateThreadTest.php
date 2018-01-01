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
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }
    
    /** @test */
    public function user_must_confrm_their_emails_before_start_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();
        
        $this->withExceptionHandling()->signIn($user);
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must confirm email, before posting anything!');
    }
    
    /** @test */
    public function a_logged_in_user_can_create_a_thread()
    {
        $user = $this->signIn();
        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($user->name)
            ->assertSee($thread->body)
            ->assertSee($thread->title);
    }

    /** @test */
    public function a_thread_requires_title()
    {
        $this->withExceptionHandling()
            ->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_body()
    {
        $this->withExceptionHandling()
            ->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    
    /** @test */
    public function a_thread_requires_a_valid_channel_id()
    {
        create('App\Channel', [], 2);
        
        $this->withExceptionHandling()
        ->publishThread(['channel_id' => null])
        ->assertSessionHasErrors('channel_id');
        
        $this->publishThread(['channel_id' => 999])
        ->assertSessionHasErrors('channel_id');
    }
    
    /////////////////////////////////////////////////////////
    //                  THREAD DELETE TESTS                //
    /////////////////////////////////////////////////////////
    

    /** @test */
    public function an_unauthorized_user_cannot_delete_thread()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        
        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);
        $this->assertDatabaseHas('threads', ['id' => $thread->id]);
    }

    /** @test */
    public function an_authorized_user_can_delete_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path())
            ->assertRedirect('/threads');
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }

    // Local Helpers

    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
