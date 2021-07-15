<?php

namespace Laratalk\Http\Controllers\Messages;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Laratalk\Events\Messages\StatusEvent;
use Laratalk\Models\Message;
use Laratalk\Models\MessageMeta;

class StatusController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'status' => sprintf(
                'required|in:%s,%s',
                Message::ACCEPT,
                Message::READ
            )
        ]);

        $data = [
            'accept_at' => now()
        ];

        if (Message::READ == Request::get('status')) {
            $data['read_at'] = now();
        }

        MessageMeta::where([
            ['message_id', Request::get('id')],
            ['to_id', Auth::id()]
        ])
        ->update($data);

        return StatusEvent::dispatch(
            [
                'id' => Request::get('id'),
                'content_to' => Auth::id(),
                'status' => Request::get('status')
            ],
            Request::get('content_by')
        );
    }
}
