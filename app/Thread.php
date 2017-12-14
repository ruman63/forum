<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**@var array */
    protected $guarded = [];
    
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

    public function addReply($attributes)
    {
        $this->replies()->create($attributes);
    }
    
    public function path($append = '')
    {
        $path = "/threads/{$this->channel->slug}/{$this->id}";
        $path .= empty($append) ? '' : '/'.$append;
        return $path;
    }
}
