<?php

namespace App\Http\Controllers;

use App\Thread;

class LockThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();

        return response('Thread successfully locked', 200);
    }

    public function destroy(Thread $thread)
    {
        $thread->unlock();

        return response('Thread successfully unlocked', 200);
    }
}
