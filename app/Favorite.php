<?php

namespace App;

use App\Traits\ActivityRecordable;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use ActivityRecordable;

    protected $fillable = ['user_id', 'favorited_id', 'favorited_type'];

    public function favorited()
    {
        return $this->morphTo();
    }
}
