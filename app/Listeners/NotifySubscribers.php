<?php

namespace App\Listeners;

use App\Events\ThreadReceivedReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedReply  $event
     * @return void
     */
    public function handle(ThreadReceivedReply $event)
    {
        $thread = $event->reply->thread;
        $thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each->notify($event->reply);
    }
}
