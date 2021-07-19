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
use Laratalk\Models\Group;
use Laratalk\Models\Message;
use Laratalk\Models\MessageRecipient;

class StoreController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'content' => 'required|max:5000',
            'chat_type' => sprintf(
                'required|in:%s,%s',
                Message::TYPE_GROUP,
                Message::TYPE_USER
            ),
            (Request::get('chat_type') === Message::TYPE_USER
                ? 'to_id' : 'group_id')
                    => 'required'
        ]);

        $message = Message::create([
            'content' => Request::get('content'),
            'by_id' => Auth::id(),
            'group_id' =>
                Request::get('chat_type') === Message::TYPE_GROUP
                    ? Request::get('group_id') : null
        ]);

        $messageResource = collect(
            new MessageResource(
                Message::find($message->id)
            )
        )->toArray();

        if (Request::get('chat_type') === Message::TYPE_USER) {

            $messageExists = Message::joinRecipient()
                ->whereMetaUser(Request::get('to_id'))
                ->whereMetaUser(Request::get('to_id'), true)
                ->exists();

            if (!$messageExists) {
                $dataProfile = [
                    'avatar' => Laratalk::gravatar(Auth::user()->email),
                    'name' => Auth::user()->name
                ];
            }

            $dataUsers = [Request::get('to_id')];
            
        }

        if (Request::get('chat_type') === Message::TYPE_GROUP) {

            $dataUsers = Group::joinGroupUser()
                ->where('laratalk_group_user.user_id', '!=', Auth::id())
                ->pluck('laratalk_group_user.user_id');

        }

        foreach ($dataUsers as $toId) {
            
            MessageRecipient::create([
                'message_id' => $message->id,
                'to_id' => $toId
            ]);

            SendEvent::dispatch(
                isset($dataProfile)
                    ? array_merge($dataProfile, $messageResource)
                    : $messageResource,
                $toId
            );
            
        }

        return Response::json($messageResource);
    }
}
