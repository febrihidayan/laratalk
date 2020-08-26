<?php

namespace Laratalk;

class Laratalk
{
    /**
     * Build a global JavaScript object for the Vue app.
     *
     * @return array
     */
    public static function scriptBuild(): array
    {
        return [
            'title' => config('laratalk.name'),
            'path' => config('laratalk.path'),
            'user_id' => auth()->user()->id,
            'profile' => config('laratalk.users'),
            'echo' => [
                'key' => env('PUSHER_APP_KEY'),
                'cluster' => env('PUSHER_APP_CLUSTER')
            ]
        ];
    }
}