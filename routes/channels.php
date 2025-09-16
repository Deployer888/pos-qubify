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
// Broadcast::channel('{model}.{id}', function ($user, $model, $id) {
//     // dd($user,$model, $id);
//     return true;
// });
if (config('app_key') &&
        config('app_secret') &&
        config('app_id') && config('app_cluster'))
{
    Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
        // logger('User ID: ' . $user->id . ' Channel ID: ' . $id); // (int) $user->id === (int) $id
        return (int) $user->id === (int) $id;
    });
    Broadcast::channel('App.Models.Customer.{id}', function ($user, $id) {
        logger('Authenticated User:', ['user' => $user, 'guard' => auth()->guard('customer')->check()]);
        return (int) $user->id === (int) $id;
    }, ['guards' => ['customer']]);
}




