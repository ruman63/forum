<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
    
    /** @test */
    public function a_user_can_view_all_threads()
    {
        $response = $this->get('/threads')
            ->assertSee($this->thread->title)
            ->assertSee($this->thread->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');
        
        $this->get("/threads/{$channel->slug}")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_username()
    {
        $this->withExceptionHandling()->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');
        
        $this->get("/threads?by=JohnDoe")
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_unanswered_threads()
    {
        $threadHavingReply = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadHavingReply->id]);
        $threadNotHavingReply = $this->thread;

        $this->get('/threads?unanswered=1')
            ->assertSee($threadNotHavingReply->body)
            ->assertDontSee($threadHavingReply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $this->withExceptionHandling()->signIn();

        
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
        
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);
        
        $threadWithNoReply = $this->thread;
            
        $response = $this->getJson("/threads?popular=1")->json();
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
        // ->see($threadByJohn->title
        // ->dontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_view_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title)
            ->assertSee($this->thread->body);
    }

    /** @test */
    public function a_user_can_fetch_replies_related_to_a_thread()
    {
        factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $response = $this->getJson($this->thread->path('replies'))->json();

        $this->assertCount(3, $response['data']);
        $this->assertEquals($response['total'], 3);
    }
}
