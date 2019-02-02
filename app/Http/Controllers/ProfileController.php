<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $activities = Activity::feed($user);

        return view('profiles/show', [
            'profileUser' => $user,
            'activities' => $activities,
        ]);
    }
}
