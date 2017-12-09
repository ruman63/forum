<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function path($append = '')
    {
        $path = "/threads/$this->id";
        $path .= empty($append) ? '' : '/'.$append;
        return $path;
    }
}
