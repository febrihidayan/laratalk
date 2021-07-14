<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Laratalk\Events\MessageEvent;
use Laratalk\Http\Resources\MessageResource;
use Laratalk\Models\Message;

class MessageController extends Controller
{
    protected function show($id)
    {
        $messages = Message::joinMeta()
            ->whereMetaUser($id)
            ->whereMetaUser($id, true)
            ->oldest('laratalk_messages.created_at')
            ->get();

        if ($messages->count()) {
            
            Message::joinMeta()
                ->whereMetaUser($id, true)
                ->update(['read_at' => now()]);
            
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

    protected function store()
    {
        $message = Message::create(request()->all());

        event(new MessageEvent($message));

        $message = new MessageResource($message);

        return response()->json($message);
    }

    protected function update($id)
    {
        $to = [
            ['from_id', $id],
            ['to_id', auth()->user()->id]
        ];
        DB::table('messages')->where($to)->update(['read_at' => now()]);
    }
}