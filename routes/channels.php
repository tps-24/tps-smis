<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
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


//Broadcast::routes(['prefix' => 'tps-smis', 'middleware' => ['web', 'auth']]);
Broadcast::routes();
Broadcast::channel('notifications.all', function ($user) {
    return true;
    //return !is_null($user); // authorize any authenticated user
});



Broadcast::channel('notifications.company', function ($user) {
    if($user->hasRole(['Super Administrator','Admin','CRO',])){
            return true;}
    if(!is_null($user->staff)){
        if($user->staff->company_id == 1) return true;
        return false;
    }
    return true;
});
// Broadcast::channel('notifications.{anouncementId}', function ($user, $anouncementId) {
//                 Log::info('NotificationEvent created', [
//                 $user
//         ]);
//             if($user->hasRole(['Super Administrator','Admin'])){
//                 return true;
//             }else if($user->hasRole(['Sir Major','	OC Coy','Instructor'])){
//                 $announcement = Announcement::findOrFail($anouncementId);
//                 if($announcement->type == 'All'){
//                     return true;
//                 }elseif($announcement->type == 'Company'){
//                     if($user->staff->company_id == $announcement->company_id)
//                     {
//                         return true;
//                     }
//                 }
//             }

//     return false;
// });




// Broadcast::channel('notifications', function ($user) {
//     // Return true if the user is authorized to listen to the channel
//     return true; // Replace with your actual authorization logic
// });

// Broadcast::channel('announcements.{userId}', function ($announcement, $user) {
   
//     return true;
//     //return ['id' => $user->id, 'name' => $user->name];
// });


