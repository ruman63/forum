<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Activity;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_an_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread),
        ]);
    }

    /** @test */
    public function it_records_an_activity_when_a_reply_is_added_to_thread()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->assertDatabaseHas('activities', [
            'type' => 'created_reply',
            'user_id' => auth()->id(),
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply),
        ]);
    }
}
