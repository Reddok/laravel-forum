<?php

namespace App\Http\Controllers;

use App\Thread;

class PinnedThreadController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->pin();

        return response('Thread successfully pinned', 200);
    }

    public function destroy(Thread $thread)
    {
        $thread->unpin();

        return response('Thread successfully unpinned', 200);
    }
}
