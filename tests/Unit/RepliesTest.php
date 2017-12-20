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
}
