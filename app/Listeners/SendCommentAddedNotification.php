<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Notifications\CommentAddedNotification;

class SendCommentAddedNotification
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
    public function handle(CommentAdded $event)
    {
        $comment = $event->comment;

        $comment->post->user->notify(new CommentAddedNotification($comment));
    }
}
