<?php

namespace App\Traits;

use App\Favorite;

trait Favorable {

    protected static function bootFavorable()
    {
        static::deleted(function($item) {
            return $item->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        if (!$this->isFavorited()) {
            $this->favorites()->create(['user_id' => auth()->id()]);
        }
    }

    public function unfavorite()
    {
        $this->favorites()
            ->where('user_id', auth()->id())
            ->get()
            ->each
            ->delete();
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

}