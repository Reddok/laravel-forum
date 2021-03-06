<?php

namespace App;

use Carbon\Carbon;
use App\Traits\Favorable;
use App\Traits\WithPolicy;
use App\Traits\ActivityRecordable;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favorable, ActivityRecordable, WithPolicy;

    protected $guarded = [];
    protected $with = ['favorites', 'owner'];
    protected $appends = ['favoritesCount', 'isFavorited', 'can', 'isBest'];
    protected $touches = ['thread'];

    public static function boot()
    {
        parent::boot();
        static::created(function ($reply) {
            Reputation::award($reply->owner, Reputation::REPLY_IN_POST_AWARD);
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return route('threads.show', [
                'channel' => $this->thread->channel->id,
                'thread' => $this->thread->id,
            ]).'#reply-'.$this->id;
    }

    public function justPublished()
    {
        return $this->created_at > (new Carbon())->subMinute();
    }

    public function detectMentioned()
    {
        preg_match_all('~@([\w-]+)~', $this->body, $matches);

        return $matches[1];
    }

    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = preg_replace('~@([\w-]+)~', '<a href="'.route('profiles.index', ['user' => '']).'/$1">$0</a>', $value);
    }

    public function isBest()
    {
        return $this->thread->best_reply_id === $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
