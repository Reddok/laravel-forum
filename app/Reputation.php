<?php

namespace App;

class Reputation {

    const THREAD_PUBLISHED_AWARD = 10;
    const REPLY_IN_POST_AWARD = 2;
    const BEST_REPLY_AWARD = 50;

    public static function award(User $relatedUser, int $points)
    {
        $relatedUser->increment('reputation', $points);
    }

}