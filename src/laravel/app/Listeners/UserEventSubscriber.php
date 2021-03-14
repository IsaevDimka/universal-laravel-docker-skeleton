<?php

declare(strict_types=1);

namespace App\Listeners;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin($event)
    {
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event)
    {
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            [self::class, 'handleUserLogin']
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            [self::class, 'handleUserLogout']
        );
    }
}
