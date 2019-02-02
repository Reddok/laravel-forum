<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    protected $key;

    public function __construct($key = 'trending_threads')
    {
        $this->key = $key;
    }

    public function get($length = -1)
    {
        return array_map('json_decode', Redis::zrevrange($this->key, 0, $length));
    }

    public function push($item)
    {
        Redis::zincrby($this->key, 1, json_encode($item));
    }

    public function reset()
    {
        Redis::del($this->key);
    }
}
