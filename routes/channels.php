<?php

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

/*Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});*/
Broadcast::channel('chat', function ($user) {
    return $user;
});
Broadcast::channel('task',function ($user){
    return $user;
});
Broadcast::channel('statistic',function ($user){
    return $user;
});
Broadcast::channel('customer',function ($user){
    return $user;
});
Broadcast::channel('lid',function ($user){
    return $user;
});
Broadcast::channel('crmStatistic',function ($user){
    return $user;
});
Broadcast::channel('act',function ($user){
    return $user;
});
Broadcast::channel('implement',function ($user){return $user;});
Broadcast::channel('volume',function ($user){return $user;});
//Broadcast::channel('chat.{toUserId}', function ($user) {
//    return $user;
//});


Broadcast::channel('master',function ($user){
    return $user;
});
