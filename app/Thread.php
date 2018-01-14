<?php

namespace App;

use App\User;
use App\Activity;
use Carbon\Carbon;
use App\RecordsActivity;
use App\Filters\ThreadsFilter;
use App\Notifications\YourMention;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadReceivedReply;

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
        $reply = $this->replies()->create($attributes);
        
        event(new ThreadReceivedReply($reply));
        
        return $reply;
    }

    public function hasChangedFor($userId)
    {
        $key = $this->visitedCacheKey($userId);
        return $this->updated_at > cache($key);
    }
    
    public function read()
    {
        $key = $this->visitedCacheKey();
        cache()->forever($key, Carbon::now());
    }
    
    public function visitedCacheKey($userId = null)
    {
        return sprintf("user.%s.read.%s", $userId ?: auth()->id(), $this->id);
    }
    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $this->attributes['slug'] = $this->incrementSlug($slug);
        } else {
            $this->attributes['slug'] = $slug;
        }
    }

    public function incrementSlug($slug)
    {
        $latest = static::whereTitle($this->title)->latest('id')->value('slug');
        if (preg_match('/(\d)$/', $latest)) {
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $latest);
        } else {
            return "{$slug}-2";
        }
    }
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
