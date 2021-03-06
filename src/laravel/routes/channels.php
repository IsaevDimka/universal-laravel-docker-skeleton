<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::routes([
    'prefix' => 'api/v1',
    'middleware' => ['auth:api'],
]);

Broadcast::channel('private-events', function (\App\Models\User $user) {
    if (in_array($user->id, [1])) {
        return true;
    }
    return false;
});

Broadcast::channel('system-events', function () {
    return true;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
