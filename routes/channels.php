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

use Musonza\Chat\Models\Conversation;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('private-App.User.{id}', function ($user, $id) {
//     return auth()->check();
// });

// Live chate private channel
Broadcast::channel('mc-chat-conversation.{conversation}', function ($user, Conversation $conversation) {
    $participants = $conversation->getParticipants();

    return !empty($participants->where('id', $user->id)->first()) ? true : false;
});
