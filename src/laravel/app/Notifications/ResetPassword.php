<?php

namespace App\Notifications;

use App\Notifications\Traits\setTagsForHorizonQueueNotificationTrait;
use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;
    use setTagsForHorizonQueueNotificationTrait;

    public function __construct($token)
    {
        $this->token = $token;
        $this->queue = config('notifications.queue');
        $this->connection = config('notifications.connection');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param \Illuminate\Notifications\AnonymousNotifiable|\App\Models\User $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $this->setTagsForHorizonQueue($notifiable, 'mail');
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url(config('app.url').'/password/reset/'.$this->token).'?email='.urlencode($notifiable->email))
            ->line('If you did not request a password reset, no further action is required.');
    }
}
