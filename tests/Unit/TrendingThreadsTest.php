<?php

namespace Tests\Unit;

use App\Trending;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp()
    {
        parent::setUp();
        $this->trending = new Trending();
        $this->trending->reset();
    }
    
    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_visited()
    {
        $thread = create('App\Thread');

        $trending = $this->trending->get();

        $this->assertCount(0, $trending);

        $this->call('GET', $thread->path());
        
        $trending = $this->trending->get();

        $this->assertCount(1, $trending);
        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
