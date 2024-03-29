<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Messages;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Events\Messages\SendEvent;
use FebriHidayan\Laratalk\Http\Resources\MessageResource;
use FebriHidayan\Laratalk\Laratalk;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\Message;
use FebriHidayan\Laratalk\Models\MessageRecipient;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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

                /**
                 * If you pass the $message variable directly,
                 * it will lose the type property
                 */
                Message::find($message->id)
            )
        )->toArray();

        if (Request::get('chat_type') === Message::TYPE_USER) {

            $messageExists = Message::whereNull('group_id')
                ->where('type', Message::CHAT)
                ->where(function ($query) {
                    return $query->where('by_id', Request::get('to_id'))
                        ->orWhereHas('recipient', function ($query) {
                            return $query->where('to_id', Request::get('to_id'));
                        });
                })
                ->where(function ($query) {
                    return $query->where('by_id', Auth::id())
                        ->orWhereHas('recipient', function ($query) {
                            return $query->where('to_id', Auth::id());
                        });
                })
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
                ->where(Config::groupUser('user_id'), '!=', Auth::id())
                ->pluck(Config::groupUser('user_id'));

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
