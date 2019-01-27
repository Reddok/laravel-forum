<?php

namespace App\Filters;

use App\User;

class ThreadFilter extends Filter
{

    protected $filters = ['by', 'popular', 'unanswered'];

    protected function by(string $username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $this->builder->where('user_id', $user->id);
    }

    protected function popular()
    {
        $this->builder->orderBy('replies_count', 'desc');
    }

    protected function unanswered()
    {
        $this->builder->doesntHave('replies');
    }

}