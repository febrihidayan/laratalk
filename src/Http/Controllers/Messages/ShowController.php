<?php

namespace Laratalk\Http\Controllers\Messages;

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Laratalk\Events\Messages\StatusEvent;
use Laratalk\Http\Resources\MessageResource;
use Laratalk\Models\Message;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $messages = Message::joinMeta()
            ->whereMetaUser($id)
            ->whereMetaUser($id, true)
            ->oldest('laratalk_messages.created_at')
            ->get();

        $messageMeta = Message::joinMeta()
            ->whereMetaUser($id, true)
            ->whereNull('laratalk_message_meta.read_at');

        if ($messages->count() && $messageMeta->count()) {

            StatusEvent::dispatch([
                'id' => $messageMeta->pluck('id'),
                'content_to' => Auth::id(),
                'status' => Message::READ
            ], $id);

            $messageMeta->update(['read_at' => now()]);
                
        }

        $user = User::find($id);

        return Response::json([
            'id' => $user->id,
            'avatar' => $user->avatar,
            'name' => $user->name,
            'email' => $user->email,
            'messages' => MessageResource::collection($messages)
        ]);
    }
}
