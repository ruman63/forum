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
        $attribute = ['user_id' => auth()->id()];
        if (!$this->isFavorited()) {
            return $this->favorites()->create($attribute);
        }
        return $this->favorites()->where($attribute)->first();
    }
    public function unfavorite()
    {
        $attribute = ['user_id' => auth()->id()];
        if ($this->isFavorited()) {
            return $this->favorites()->where($attribute)->first()->delete();
        }
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
}
