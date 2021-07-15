<?php

namespace Laratalk\Http\Controllers\Messages;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Laratalk\Events\Messages\SendEvent;
use Laratalk\Http\Resources\MessageResource;
use Laratalk\Laratalk;
use Laratalk\Models\Message;
use Laratalk\Models\MessageMeta;

class StoreController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'content' => 'required|max:5000',
            'to_id' => 'required'
        ]);

        $messageExists = Message::joinMeta()
            ->whereMetaUser(Request::get('to_id'))
            ->whereMetaUser(Request::get('to_id'), true)
            ->exists();

        if (!$messageExists) {
            $data = [
                'avatar' => Laratalk::getUserAvatar(Auth::email()),
                'name' => Auth::user()->name
            ];
        }

        $message = Message::create([
            'content' => Request::get('content'),
            'from_id' => Auth::id()
        ]);

        MessageMeta::create([
            'message_id' => $message->id,
            'to_id' => Request::get('to_id')
        ]);

        $message = collect(
            new MessageResource($message)
        )->toArray();

        SendEvent::dispatch(
            isset($data) ? array_merge($data, $message) : $message,
            Request::get('to_id')
        );

        return Response::json($message);
    }
}
