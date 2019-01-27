<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index(User $user)
    {
        $activities = Activity::feed($user);

        return view('profiles/show', [
            'profileUser' => $user,
            'activities' => $activities
        ]);
    }

}
