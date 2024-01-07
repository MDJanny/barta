<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAddedNotification extends Notification
{
    use Queueable;

    private $commentAuthorFirstName;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Comment $comment)
    {
        $this->commentAuthorFirstName = strtok($this->comment->user->name, ' ');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Comment')
            ->line("{$this->commentAuthorFirstName} commented on your post.")
            ->action('View Post', url('/post/'.$this->comment->post->uuid))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->commentAuthorFirstName} commented on your post.",
            'link' => "/post/{$this->comment->post->uuid}",
        ];
    }
}
