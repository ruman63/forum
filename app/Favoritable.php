<?php

namespace App;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        if (!$this->isFavorited()) {
            return $this->favorites()->create([ 'user_id' => auth()->id() ]);
        }
        return $this->favorites()->where([ 'user_id' => auth()->id() ])->first();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function isFavorited()
    {
        return $this->favorites->count();
    }
}
