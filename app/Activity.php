<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['type', 'user_id', 'subject_type', 'subject_id', 'created_at'];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed(User $user, int $limit = 50)
    {
        return static::where(['user_id' => $user->id])->latest()->with('subject')->take($limit)->get()->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });
    }
}
