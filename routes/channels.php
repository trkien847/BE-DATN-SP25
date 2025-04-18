<?php

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('notifications', function ($user) {
    return $user !== null;
});

Broadcast::channel('notifications.{userId}.admin', function ($user, $userId) {
    $result = auth()->check() && (int) $user->id === (int) $userId && $user->role_id === 3;
    return $result;
});
