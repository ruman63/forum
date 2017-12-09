<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Collection;

class RepliesTest extends TestCase
{
    use DatabaseMigrations;
    public function setUp()
    {
        parent::setUp();
        $this->reply = factory('App\Reply')->create();
    }
    /** @test */
    public function it_test_thread_has_a_owner_relationship()
    {
        $this->assertInstanceOf('App\User', $this->reply->owner);
    }
}
