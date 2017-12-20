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


    /** @test */
    public function it_fetches_all_user_names_matching_name_query()
    {
        create('App\User', ['name' => 'John Doe']);
        create('App\User', ['name' => 'John Doe2']);
        create('App\User', ['name' => 'Jane Doe2']);

        $result = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertEquals(['John Doe', 'John Doe2'], $result->json());
    }
}
