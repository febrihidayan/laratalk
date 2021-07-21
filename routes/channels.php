<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Laratalk\Models\Message;

Broadcast::channel('chat', function ($user) {
    return Auth::check();
});

Broadcast::channel('online', function ($user) {
    if (Auth::check()) {
        return [
            'id' => $user->id,
            'chat_type' => Message::TYPE_USER
        ];
    }
});