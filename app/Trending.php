<?php
namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function push(Thread $thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'link' => $thread->path()
        ]));
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    public function cacheKey()
    {
        return app()->environment('testing') ? 'tests_trending_threads' : 'trending_threads';
    }
}
