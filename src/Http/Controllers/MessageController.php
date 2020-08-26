<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Laratalk\Events\MessageEvent;
use Laratalk\Http\Resources\MessageResource;
use Laratalk\Models\Message;

class MessageController extends Controller
{
    protected function show($id)
    {
        $to = [
            ['from_id', $id],
            ['to_id', auth()->user()->id]
        ];

        $messages = Message::with('userFrom')->where($to);

        $first = $messages;

        if ($first->exists()) {

            DB::table('laratalk_messages')->where($to)->update(['read_at' => now()]);
        }

        $messages = $messages->orWhere([
            ['from_id', auth()->user()->id],
            ['to_id', $id]
        ])->get();

        $messages = MessageResource::collection($messages);

        return response()->json($messages);
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