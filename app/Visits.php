<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits {

    protected $key;

    public function __construct($item)
    {
        $this->key = get_class() . '|' . $item->id . '|visits';
    }

    public function count()
    {
        return Redis::get($this->key) ?? 0;
    }

    public function record()
    {
        Redis::incr($this->key);
    }

    public function reset()
    {
        Redis::del($this->key);
    }
}