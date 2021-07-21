<?php

namespace Laratalk\Http\Controllers\Messages;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Laratalk\Events\Messages\StatusEvent;
use Laratalk\Models\Message;
use Laratalk\Models\MessageRecipient;

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

        $message = Message::find(Request::get('id'));

        if ($message->type === Message::CHAT) {
            $data = [
                'accept_at' => now()
            ];
    
            if (Request::get('status') === Message::READ) {
                $data['read_at'] = now();
            }
    
            MessageRecipient::where([
                ['message_id', Request::get('id')],
                ['to_id', Auth::id()]
            ])
            ->update($data);
    
            return StatusEvent::dispatch(
                [
                    'id' => Request::get('id'),
                    'content_to' => $message->group_id ?? Auth::id(),
                    'status' => Request::get('status'),
                    'chat_type' => $message->chatType()
                ],
                Request::get('content_by')
            );
        }

    }
}
