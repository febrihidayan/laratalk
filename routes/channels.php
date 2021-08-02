<?php

use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

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