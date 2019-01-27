<?php

namespace App;

use App\Events\ReplyPostedEvent;
use App\Filters\ThreadFilter;
use App\Notifications\ThreadWasUpdated;
use App\Traits\ActivityRecordable;
use App\Traits\WithPolicy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use ActivityRecordable, WithPolicy, Searchable;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];
    protected $appends = ['isSubscribedTo', 'can'];
    protected $casts = [
        'user_id' => 'integer',
        'channel_id' => 'integer',
        'best_reply_id' => 'integer',
        'locked' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function($thread) {
            $thread->replies->each->delete();
        });

        static::created(function($thread) {
            $thread->slug = $thread->title;
            $thread->save();
        });

    }

    public function toSearchableArray()
    {
        $result = $this->toArray() + [
                'path' => $this->path(),
                'visits' => $this->visits->count()
            ];
        $result['creator']['path'] = $this->creator->path();

        return $result;
    }

    public function path(string $ending = '')
    {
        return '/threads/' . $this->channel->slug . '/' . $this->slug . ($ending? '/' . $ending : '');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply(array $reply)
    {
        $reply = $this->replies()->create($reply);
        event(new ReplyPostedEvent($reply));
        return $reply;
    }


    public function subscribe($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $this->subscriptions()->create(['user_id' => $userId]);
        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $this->subscriptions()->where(['user_id' => $userId])->delete();
    }

    public function scopeFilter(Builder $query, ThreadFilter $filters)
    {
        return $filters->apply($query);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where(['user_id' => auth()->id() ?? 0])
            ->exists();
    }

    public function hasUpdatesFor(User $user)
    {
        $key = $user->getThreadReadCacheKey($this);
        return cache($key) < $this->updated_at;
    }

    public function getVisitsAttribute($value)
    {
        return $value? $value : new Visits($this);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($slug)
    {
        $slug = str_slug($slug);

        if(Thread::whereSlug($slug)->exists()) {
            $slug = $slug . '-' . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function markAsBest($reply)
    {
        $this->best_reply_id = $reply->id;
        $this->save();
    }

    public function lock()
    {
        $this->locked = true;
        $this->save();
    }

    public function unlock()
    {
        $this->locked = false;
        $this->save();
    }
}
