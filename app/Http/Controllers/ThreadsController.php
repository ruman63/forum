<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Channel;
use App\Rules\SpamFree;
use Illuminate\Http\Request;
use App\Filters\ThreadsFilter;
use Illuminate\Support\Facades\Redis;
use App\Trending;
use App\Rules\Recaptcha;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadsFilter $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);
        if (request()->wantsJson()) {
            return $threads;
        }
        $trendingThreads = $trending->get();
        return view('threads.index', compact('threads', 'trendingThreads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {
        request()->validate([
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha],
        ]);
        $thread = Thread::create([
            'title' => title_case(request('title')),
            'body' => request('body'),
            'channel_id' => request('channel_id'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->to($thread->path())->with('flash', "Thread created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        $thread->read();

        $trending->push($thread);
        $thread->increment('visits');
        
        return view('threads.show', compact('thread'));
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
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        
        $thread->delete();

        return redirect('/threads');
    }

    protected function getThreads(Channel $channel, ThreadsFilter $filters)
    {
        $threads = Thread::latest()->filter($filters);
        
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(15);
    }
}
