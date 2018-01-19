<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class RepliesTest extends TestCase
{
    use DatabaseMigrations;
    public function setUp()
    {
        parent::setUp();
        $this->reply = create('App\Reply');
    }
    /** @test */
    public function it_test_thread_has_a_owner_relationship()
    {
        $this->assertInstanceOf('App\User', $this->reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_published_just_a_minute_ago()
    {
        $reply = create('App\Reply');
        $this->assertTrue($reply->wasJustPublished());

        $reply = create('App\Reply', ['created_at' => Carbon::now()->subMinute(2)]);
        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_knows_all_the_mentioned_users_names()
    {
        $reply = new \App\Reply([
            'body' => "@john, @jane_doe @someone-else!"
        ]);
        $this->assertEquals($reply->mentionedUsers(), ['john', 'jane_doe', 'someone-else']);
    }
    
    /** @test */
    public function it_knows_if_it_is_a_best_reply()
    {
        $reply = create('App\Reply');
        $this->assertFalse($reply->isBest());
        
        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    public function it_wraps_mentioned_users_names_int_the_body_within_anchor_tag()
    {
        $reply = new \App\Reply([
            'body' => "hello, @john!"
        ]);
        $this->assertEquals($reply->body, 'hello, <a href="/profiles/john">@john</a>!');
    }

    /** @test */
    public function it_cleans_the_body_field_for_unwanted_html_tags()
    {
        $reply = create('App\Reply', [
            'body' => "<script>alert('foo')</script><p>Hello there <a href=\"#\" onclick=\"alert('gotcha');\">Click Me</a></p>"
        ]);
        $this->assertEquals($reply->body, "<p>Hello there <a href=\"#\">Click Me</a></p>");
    }
}
