<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function thread_creator_can_mark_a_best_reply()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 3);
        
        $this->assertFalse($replies[1]->isBest());
        $this->post(route('replies.best', ['reply' => $replies[1]->id]));
        $this->assertTrue($replies[1]->fresh()->isBest());
    }


    /** @test */
    public function only_the_thread_creator_can_mark_a_best_reply()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 3);

        $this->signIn(create('App\User'));
        $this->post(route('replies.best', ['reply' => $replies[1]->id]))->assertStatus(403);
        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function it_updates_thread_accordingly_when_a_best_reply_is_deleted()
    {
        \DB::statement('PRAGMA foreign_keys=ON;');
        
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $this->post(route('replies.best', ['reply' => $reply->id]));

        $this->assertTrue($reply->fresh()->isBest());
        $reply->delete();
        $this->assertNull($thread->fresh()->best_reply_id);
    }
}
