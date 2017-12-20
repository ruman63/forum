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
        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each
            ->notify(new YourMention($event->reply));
    }
}
