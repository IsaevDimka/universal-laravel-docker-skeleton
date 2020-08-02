<?php

namespace App\Notifications;

use App\Notifications\Traits\setTagsForHorizonQueueNotificationTrait;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;
    use setTagsForHorizonQueueNotificationTrait;

    public function __construct()
    {
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
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(60), ['user' => $notifiable->id]
        );

        return str_replace('/api', '', $url);
    }
}
