<?php

namespace App;

use App\Activity;
use App\Filters\ThreadsFilter;
use Illuminate\Database\Eloquent\Model;
use App\RecordsActivity;

class Thread extends Model
{
    use RecordsActivity;
    
    protected $guarded = [];

    protected $with = ['owner', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }
    
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function scopeFilter($query, ThreadsFilter $filters)
    {
        return $filters->apply($query);
    }

    public function addReply($attributes)
    {
        return $this->replies()->create($attributes);
    }
    
    public function path($append = '')
    {
        $path = "/threads/{$this->channel->slug}/{$this->id}";
        $path .= empty($append) ? '' : '/'.$append;
        return $path;
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
            
        return $this;
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
