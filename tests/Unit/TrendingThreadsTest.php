<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_visited()
    {
        Redis::del('trending_threads');
        
        $thread = create('App\Thread');

        $trending = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(0, $trending);

        $this->call('GET', $thread->path());
        
        $trending = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trending);
    }
}
