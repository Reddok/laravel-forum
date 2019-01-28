<?php

namespace App;

use App\Traits\WithPolicy;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, WithPolicy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];
    protected $appends = ['can'];
    protected $casts = [
        'confirmed' => 'boolean',
        'is_admin' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $reputation;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
//        $this->reputation = new Reputation($this);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }

    public function path()
    {
        return route('profiles.index', $this);
    }

    public function getThreadReadCacheKey(Thread $thread)
    {
        return sprintf('user:%d-visit:%d', $this->id, $thread->id);
    }

    public function read(Thread $thread)
    {
        $key = $this->getThreadReadCacheKey($thread);
        cache()->forever($key, Carbon::now());
    }

    public function getAvatarPathAttribute($value) {
        return  asset('/storage/' . ($value?: 'avatars/default.png'));
    }
}
