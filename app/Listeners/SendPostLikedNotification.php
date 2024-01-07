<?php

namespace App\Listeners;

use App\Events\PostLiked;
use App\Notifications\PostLikedNotification;

class SendPostLikedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostLiked $event): void
    {
        $like = $event->like;

        $like->post->user->notify(new PostLikedNotification($like));
    }
}
