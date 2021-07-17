<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laratalk\Events\Messages\StatusEvent;
use Laratalk\Laratalk;
use Laratalk\Models\Message;

class LaratalkController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $messages = Message::joinMeta()
            ->where('laratalk_message_recipient.to_id', $user->id)
            ->whereNull('laratalk_message_recipient.accept_at');

        if ($messages->count()) {

            foreach ($messages->get()->unique('by_id') as $item) {

                $idMessages = $messages->get()
                    ->where('by_id', $item->by_id)
                    ->pluck('id');

                StatusEvent::dispatch(
                    [
                        'id' => $idMessages,
                        'content_to' => $item->to_id,
                        'status' => Message::ACCEPT
                    ],
                    $item->by_id
                );
                
            }

            $messages->update([
                'accept_at' => now()
            ]);

        }

        return view('laratalk::layout')
            ->withScripts([
                'title' => Config::get('laratalk.name'),
                'path' => Config::get('laratalk.path'),
                'profile' => [
                    'id' => $user->id,
                    'avatar' => Laratalk::gravatar($user->email),
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'translations' => Laratalk::availableTranslations(),
                'echo' => Config::get('broadcasting.connections.pusher')
            ]);
    }
}
