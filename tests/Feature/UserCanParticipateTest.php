<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class UserCanParticipateTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }
    /** @test */
    public function an_unauthorized_user_can_not_create_replies()
    {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/reply')
            ->assertRedirect('login');
    }

    /** @test */
    public function a_reply_to_thread_requires_body()
    {
        $this->withExceptionHandling()->signIn();
        
        $reply = make('App\Reply', ['body' => null]);

        $this->post($this->thread->path('reply'), $reply->toArray())
            ->assertSessionHasErrors('body');
    }
    
    
    /** @test */
    public function a_user_can_participate_in_forum_if_logged_in()
    {
        $this->signIn();
        
        $reply = make('App\Reply');

        $this->post($this->thread->path('reply'), $reply->toArray());

        $this->get($this->thread->path())->assertSee($reply->body);
    }

    /** @test */
    public function unauthorized_user_cannot_delete_a_reply()
    {
        $reply = create('App\Reply');
        $this->withExceptionHandling();
        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete("/replies/{$reply->id}")->assertStatus(403);

        $this->assertDatabaseHas('replies', [ 'id' => $reply->id ]);
    }

    /** @test */
    public function authorized_user_can_delete_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]);
    }
}
