<?php

namespace Laratalk;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Laratalk
{
    /**
     * Build a global JavaScript object for the Vue app.
     *
     * @return array
     */
    public static function scriptBuild(): array
    {
        $user = Auth::user();

        return [
            'title' => Config::get('laratalk.name'),
            'path' => Config::get('laratalk.path'),
            'profile' => [
                'id' => $user->id,
                'avatar' => self::getUserAvatar($user->email),
                'name' => $user->name,
                'email' => $user->email,
            ],
            'echo' => Config::get('broadcasting.connections.pusher')
        ];
    }

    /**
     * Get avatar user
     * 
     * @var string $email
     * @return string
     */
    public static function getUserAvatar($email)
    {
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=40";
    }
}