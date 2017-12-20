<?php

namespace App\Listeners;

use App\User;
use App\Notifications\YourMention;
use App\Events\ThreadReceivedReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedReply $event)
    {
        collect($event->reply->mentionedUsers())
        ->map(function ($name) {
            return User::whereName($name)->first();
        })
        ->filter()
        ->each->notify(new YourMention($event->reply));
    }
}
