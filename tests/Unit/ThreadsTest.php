<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Collection;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }
    /** @test */
    public function it_test_thread_has_a_owner_relationship()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    public function a_thread_has_many_replies_relationship()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }
}
