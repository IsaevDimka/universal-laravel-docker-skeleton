<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Traits\setTagsForHorizonQueueNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use setTagsForHorizonQueueNotificationTrait;

    /**
     * @var string
     */
    private const CHANNEL = 'mail';

    /**
     * @var array
     */
    protected $data = [
        'subject' => null,
        'replyTo' => null,
        'line_1' => null,
        'line_2' => null,
        'action_label' => null,
        'action_url' => null,
        'line_3' => null,
    ];

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;

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
        $this->setTagsForHorizonQueue($notifiable, self::CHANNEL);
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param \Illuminate\Notifications\AnonymousNotifiable|\App\Models\User $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage();
        if (! empty($this->data['subject'])) {
            $message->subject($this->data['subject']);
        }
        if (! empty($this->data['replyTo'])) {
            $message->replyTo($this->data['replyTo']);
        }
        if (! empty($this->data['line_1'])) {
            $message->line($this->data['line_1']);
        }
        if (! empty($this->data['line_2'])) {
            $message->line($this->data['line_2']);
        }
        if (! empty($this->data['action_label']) || ! empty($this->data['action_url'])) {
            $message->action($this->data['action_label'], $this->data['action_url']);
        }
        if (! empty($this->data['line_3'])) {
            $message->line($this->data['line_3']);
        }

        return $message;
    }
}
