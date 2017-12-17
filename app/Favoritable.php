<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }
    
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
        return $this->favorites()->where($attribute)->get()->each->delete();
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
