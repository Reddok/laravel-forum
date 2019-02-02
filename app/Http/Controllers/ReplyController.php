<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Rules\Spam;
use Illuminate\Http\Request;
use App\Http\Requests\ReplyCreateRequest;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index(string $channel, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store(string $channel, Thread $thread, ReplyCreateRequest $request)
    {
        if ($thread->locked) {
            return response('Thread has been locked and cant receive new replies', 422);
        }

        return response($thread->addReply([
            'body' => $request->post('body'),
            'user_id' => auth()->id(),
        ])
            ->load('owner'), 201);
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        return ['status' => 'Your reply has been successfully deleted from thread'];
    }

    public function update(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $this->validate($request, [
            'body' => ['required', new Spam],
        ]);
        $reply->update(['body' => $request->get('body')]);

        return $reply;
    }
}
