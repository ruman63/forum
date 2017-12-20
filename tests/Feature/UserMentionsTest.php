<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserMentionsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function when_a_user_is_mentioned_in_a_reply_he_is_notified()
    {
        $this->signIn();

        $john = create('App\User', ['name' => 'JohnDoe']);
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => '@JohnDoe take a look.'
        ]);

        $this->post($thread->path(). '/replies', $reply->toArray());
        $this->assertCount(1, $john->notifications);
    }
}
