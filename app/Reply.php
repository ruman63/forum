<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Favorite;

class Reply extends Model
{
    use Favoritable;
    
    protected $fillable = ['body', 'thread_id', 'user_id'];
    protected $with = ['owner', 'favorites'];

    
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
