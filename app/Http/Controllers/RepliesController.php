<?php

namespace App\Http\Controllers;

use App\Reply;
use App\User;
use App\Thread;
use App\Rules\SpamFree;
use Illuminate\Http\Request;
use App\Notifications\YourMention;
use App\Http\Requests\CreateReplyRequest;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreateReplyRequest $request)
    {
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
            ])->load('owner');
            
        preg_match_all('/\\@([^\\s\\.]+)/', request('body'), $matches);

        foreach ($matches[1] as $name) {
            if ($user = User::whereName($name)->first()) {
                $user->notify(new YourMention($reply));
            }
        }
        return $reply;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        try {
            request()->validate(['body' => ['required ', new SpamFree] ]);
            $reply->update(['body' => request('body')]);
        } catch (\Exception $e) {
            return response("Your Reply contains Spam!!", 422);
        }
        return $reply->load('owner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if (request()->expectsJson()) {
            return response([ 'status' => 'reply deleted!']);
        }
        
        return back()->with('flash', 'You reply was deleted');
    }
}
