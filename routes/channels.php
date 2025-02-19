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

Broadcast::channel('chat', function ($user, $id) {
    return true;
});

// Broadcast::channel('notifications', function ($user) {
//     // Return true if the user is authorized to listen to the channel
//     return true; // Replace with your actual authorization logic
// });

// Broadcast::channel('announcements.{userId}', function ($announcement, $user) {
   
//     return true;
//     //return ['id' => $user->id, 'name' => $user->name];
// });


