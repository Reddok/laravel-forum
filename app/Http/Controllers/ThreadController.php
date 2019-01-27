<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilter;
use App\Rules\Recaptcha;
use App\Rules\Spam;
use App\Thread;
use App\Trending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Zttp\Zttp;

class ThreadController extends Controller
{

    protected $trending;

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
        $this->trending = new Trending(app()->environment('testing') ? 'test_trending' : 'trending_threads');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel = null, ThreadFilter $filters)
    {

        $threads = Thread::filter($filters);

        if ($channel && $channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads->latest();
        $threads = $threads->paginate(5);

        $trending = $this->trending->get(4);
        return view('threads/index', compact('threads', 'trending'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => ['required', new Spam],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha]
        ]);

        $thread = Thread::create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'user_id' => auth()->id(),
            'channel_id' => $request->get('channel_id'),
            'slug' => str_slug($request->get('title'))
        ]);

        if ($request->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path())->with('flash', 'The thread has been successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(string $channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $this->trending->push(['title' => $thread->title, 'path' => $thread->path()]);
        $thread->visits->record();
        return view('threads/show',compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread, Request $request)
    {
        $this->authorize('update', $thread);

        $thread->update($request->validate([
            'title' => 'required',
            'body' => ['required', new Spam],
        ]));

        return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
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
