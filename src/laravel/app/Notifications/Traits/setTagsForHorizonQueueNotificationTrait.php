<?php


namespace App\Notifications\Traits;

use App\Models\User;
use Illuminate\Notifications\AnonymousNotifiable;

/**
 * Helper trait for laravel horizon queue support tags
 * Trait setTagsForHorizonQueueNotificationTrait
 * @package App\Notifications\Traits
 */
trait setTagsForHorizonQueueNotificationTrait
{

    protected $notify_user_id = null;
    protected $notify_type = null;
    protected $additional_tag = null;

    /**
     * @param AnonymousNotifiable|User $notifiable
     * @param string|null              $channel
     */
    private function setTagsForHorizonQueue($notifiable, string $channel = null)
    {
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable)
        {
            $this->notify_type = 'AnonymousNotifiable';
        }elseif ($notifiable instanceof User){
            $this->notify_type = 'UserNotifiable';
            $this->notify_user_id = $notifiable->id;
        }
        /**
         * set additional tags
         */
        switch ($channel)
        {
            case 'telegram':
                $this->additional_tag = 'telegram_chat_id: '.$this->getTelegramChatId($notifiable);
            break;
            case 'mail':
                $this->additional_tag = 'email: ' . $this->getEmail($notifiable);
            break;
            case 'sms':
                $this->additional_tag = 'phone: ' . $this->getPhone($notifiable);
            break;
        }
    }

    /**
     * for horizon queue
     * @return array
     */
    public function tags()
    {
        $tags = [$this->notify_type];
        if ($this->additional_tag)
        {
            $tags = array_merge($tags, [$this->additional_tag]);
        }
        if ($this->notify_user_id)
        {
            $tags = array_merge($tags, ['user_id: '.$this->notify_user_id]);
        }
        return $tags;
    }

    /**
     * @param AnonymousNotifiable|User $notifiable
     *
     * @return string|null
     */
    private function getEmail($notifiable)
    {
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            return $notifiable->routes['mail'];
        } elseif ($notifiable instanceof User) {
            return $notifiable->routeNotificationForMail();
        }else{
            return null;
        }
    }

    /**
     * @param AnonymousNotifiable|User $notifiable
     *
     * @return string|null
     */
    private function getTelegramChatId($notifiable)
    {
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            return $notifiable->routes['telegram'];
        } elseif ($notifiable instanceof User) {
            return $notifiable->routeNotificationForTelegram();
        }else{
            return null;
        }
    }

    /**
     * @param AnonymousNotifiable|User $notifiable
     *
     * @return string|null
     */
    private function getPhone($notifiable)
    {
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            return $notifiable->routes['sms'];
        } elseif ($notifiable instanceof User) {
            return $notifiable->routeNotificationForSms();
        }else{
            return null;
        }
    }
}
