<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Trending;
use App\Rules\Spam;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use App\Filters\ThreadFilter;

class ThreadController extends Controller
{
    protected $trending;

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
        $this->trending = new Trending(app()->environment('testing') ? 'test_trending' : 'trending_threads');
    }

    public function index(Channel $channel = null, ThreadFilter $filters)
    {
        $threads = Thread::filter($filters);

        if ($channel && $channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads->orderBy('pinned', 'DESC')->latest();
        $threads = $threads->paginate(5);

        if (request()->wantsJson()) {
            return $threads;
        }

        $trending = $this->trending->get(4);

        return view('threads/index', compact('threads', 'trending'));
    }


    public function create()
    {
        return view('threads/create');
    }

    public function store(Request $request, Recaptcha $recaptcha)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => ['required', new Spam],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha],
        ]);

        $thread = Thread::create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'user_id' => auth()->id(),
            'channel_id' => $request->get('channel_id'),
            'slug' => str_slug($request->get('title')),
        ]);

        if ($request->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path())->with('flash', 'The thread has been successfully created!');
    }

    public function show(string $channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $this->trending->push(['title' => $thread->title, 'path' => $thread->path()]);
        $thread->visits->record();

        return view('threads/show', compact('thread'));
    }

    public function edit(Thread $thread)
    {
        //
    }

    public function update($channel, Thread $thread, Request $request)
    {
        $this->authorize('update', $thread);

        $thread->update($request->validate([
            'title' => 'required',
            'body' => ['required', new Spam],
        ]));

        return $thread;
    }

    public function destroy(string $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('threads.index'))
            ->with('flash', 'The thread has been successfully deleted!');
    }
}
