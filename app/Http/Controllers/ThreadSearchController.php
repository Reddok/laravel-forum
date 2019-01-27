<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class ThreadSearchController extends Controller
{

    public function index(Request $request)
    {
        $q = $request->get('q');

        if ($request->wantsJson()) {
            $threads=  Thread::search($q)->paginate(25);
            return $threads;
        }

        return view('threads/search', compact('q'));
    }

}
