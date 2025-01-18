<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;

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

Broadcast::channel('App\Models\User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);
    if (!$chat) return false;
    return $user->id === $chat->user_id || $user->id === $chat->admin_id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    $chat = \App\Models\Chat::find($id);
    return $chat && ($user->id === $chat->user_id || $user->id === $chat->agent_id);
});

Broadcast::channel('private-chat.{chatId}', function ($user, $chatId) {
    $chat = \App\Models\Chat::find($chatId);
    return $chat && ($user->id === $chat->user_id || $user->role === 'agent');
});
