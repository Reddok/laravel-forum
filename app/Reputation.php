<?php

namespace App;

class Reputation {

    const THREAD_PUBLISHED_AWARD = 10;
    const REPLY_IN_POST_AWARD = 2;
    const BEST_REPLY_AWARD = 50;
    const REPLY_FAVORITED_AWARD = 5;

    public static function award(User $relatedUser, int $points)
    {
        $relatedUser->increment('reputation', $points);
    }

    public static function rewoke(User $relatedUser, int $points)
    {
        $relatedUser->decrement('reputation', $points);
    }
}