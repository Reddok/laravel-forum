<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class ThreadSubscription extends Model
{
    protected $fillable = ['user_id', 'thread_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function notify(Notification $event)
    {
        $this->user->notify($event);
    }
}
