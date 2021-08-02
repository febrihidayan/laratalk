<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Messages;

use FebriHidayan\Laratalk\Events\Messages\PullMessageEvent;
use FebriHidayan\Laratalk\Models\GroupUser;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PullMessageController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'id' => 'required',
            'user_id' => 'required',
            'chat_type' => 'required'
        ]);

        Message::where([
                ['by_id', Auth::id()],
                ['id', Request::get('id')]
            ])
            ->update([
                'content' => null,
                'type' => Message::PULL_MESSAGE
            ]);

        if (Request::get('chat_type') === Message::TYPE_GROUP) {

            $sendUser = GroupUser::where([
                    ['group_id', Request::get('user_id')],
                    ['user_id', '!=', Auth::id()]
                ])
                ->get()
                ->pluck('user_id');

            $idUserTarget = Request::get('user_id');

        } else {

            $idUserTarget = Auth::id();
            $sendUser = [Request::get('user_id')];

        }

        $targetData = [
            'id' => Request::get('id'),
            'content_type' => Message::PULL_MESSAGE,
            'chat_type' => Request::get('chat_type'),
            'target_user_id' => $idUserTarget
        ];

        foreach ($sendUser as $id) {
            event(
                new PullMessageEvent($targetData, $id)
            );
        };

        $targetData['target_user_id'] = Request::get('user_id');

        return Response::json($targetData);
    }
}
