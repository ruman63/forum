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
            ->post('/threads/some-channel/1/replies')
            ->assertRedirect('login');
    }

    /** @test */
    public function a_reply_to_thread_requires_body()
    {
        $this->withExceptionHandling()->signIn();
        
        $reply = make('App\Reply', ['body' => null]);

        $this->post($this->thread->path() .'/replies', $reply->toArray())
            ->assertStatus(422);
    }
    
    
    /** @test */
    public function a_user_can_participate_in_forum_if_logged_in()
    {
        $this->signIn();
        
        $reply = make('App\Reply');

        $this->post($this->thread->path(). '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $this->thread->fresh()->replies_count);
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
        $reply = create('App\Reply', ['user_id' => auth()->id(), 'thread_id' => $this->thread->id]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]);
        $this->assertEquals(0, $this->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthorized_user_cannot_update_a_reply()
    {
        $reply = create('App\Reply');
        $this->withExceptionHandling();
        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('/login');
            
        $this->signIn();
        $this->patch("/replies/{$reply->id}")->assertStatus(403);

        $this->assertDatabaseHas('replies', [ 'id' => $reply->id, 'body' => $reply->body ]);
    }

    /** @test */
    public function an_exception_is_thrown_if_a_user_submits_a_spam_reply()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->post($thread->path() .'/replies', $reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function user_may_not_reply_more_than_once_per_minute()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'A Decent Reply'
        ]);

        $this->post($thread->path() .'/replies', $reply->toArray())
            ->assertStatus(200);
        $this->post($thread->path() .'/replies', $reply->toArray())
            ->assertStatus(429);
    }

    /** @test */
    public function authorized_user_can_update_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $updatedBody = "its updated now!";
        $this->patch("/replies/{$reply->id}", ['body' => $updatedBody]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedBody
        ]);
    }
}
