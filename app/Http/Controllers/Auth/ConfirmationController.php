<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmationController extends Controller
{

    public function index(Request $request)
    {
        $token = $request->get('token');
        $message = 'You are successfully confirmed your email!';

        if (!$token || !($user = User::where(['confirmation_token' => $token])->first())) {
            $message = '{"message":"Invalid Token!","level":"danger"}';
        } else {
            $user->confirm();
        }

        return redirect(route('threads.index'))
            ->with('flash', $message);
    }

}
