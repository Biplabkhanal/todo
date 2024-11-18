<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notification', function () {
    return true;
});

Broadcast::channel('chat.{rid}', function (User $user, int $rid) {
    return $user->id === $rid;
});

Broadcast::channel('type.{rid}', function (User $user, int $rid) {
    return $user->id === $rid;
});

Broadcast::channel('messages.{rid}', function (User $user, int $rid) {
    return $user->id === $rid;
});
