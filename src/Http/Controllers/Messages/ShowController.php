<?php

namespace Laratalk\Http\Controllers\Messages;

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Laratalk\Events\Messages\StatusEvent;
use Laratalk\Http\Resources\MessageResource;
use Laratalk\Http\Resources\UserGroupResource;
use Laratalk\Laratalk;
use Laratalk\Models\Group;
use Laratalk\Models\Message;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $type = Request::get('type');

        if (
            is_null($type) &&
            !in_array($type, [
                Message::TYPE_USER, Message::TYPE_GROUP
            ])
        ) {
            return Response::json([]);
        }

        if ($type === Message::TYPE_GROUP) {

            $select = [
                'laratalk_groups.name as group_name',
                'laratalk_groups.avatar as group_avatar',
            ];

        }
        else {

            $select = [
                'laratalk_message_recipient.*',
            ];
            
        }

        $messages = Message::select([
                'laratalk_messages.*',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                ...$select
            ])
            ->when($type, function($query, $type) use ($id) {

                if ($type === Message::TYPE_USER) {

                    return $query
                        ->joinRecipientUser()
                        ->where( function($query) use($id) {
                            return $query
                                ->whereMetaUser($id)
                                ->whereMetaUser($id, true);
                        })
                        ->where('laratalk_messages.type', Message::CHAT)
                        ->whereNull('laratalk_messages.group_id');

                }

                if ($type === Message::TYPE_GROUP) {

                    return $query
                        ->joinGroup()
                        ->joinUser()
                        ->where('laratalk_groups.id', $id)
                        ->whereNotNull('laratalk_messages.group_id');

                }
            })
            ->oldest('laratalk_messages.created_at')
            ->get();

        $messageRecipient = Message::joinRecipient()
            ->when($type, function($query, $type) use ($id) {

                if ($type === Message::TYPE_USER) {

                    return $query->whereMetaUser($id, true);

                }

                if ($type === Message::TYPE_GROUP) {
                    
                    return $query
                        ->where('laratalk_messages.group_id', $id)
                        ->where('laratalk_message_recipient.to_id', Auth::id());

                }
            })
            ->where('laratalk_messages.type', Message::CHAT)
            ->whereNull('laratalk_message_recipient.read_at');

        if ($messages->count() && $messageRecipient->count()) {

            StatusEvent::dispatch([
                'id' => $messageRecipient->pluck('id'),
                'content_to' => Auth::id(),
                'status' => Message::READ
            ], $id);

            $messageRecipient->update(['read_at' => now()]);
                
        }

        $data = [
            'chat_type' => $type,
            'messages' => MessageResource::collection($messages)
        ];

        if (Request::get('type') == Message::TYPE_USER) {
            $first = User::find($id);

            $data += [
                'id' => $first->id,
                'avatar' => Laratalk::gravatar($first->email),
                'name' => $first->name,
                'email' => $first->email,
            ];
        } else {
            $first = Group::with('users')
                ->find($id);

            $data += [
                'id' => $first->id,
                'avatar' => $first->avatar,
                'name' => $first->name,
                'description' => $first->description,
                'last_time' => Laratalk::lastTime($first->created_at, true),
                'time' => $first-> created_at->format('H.i'),
                'users' => UserGroupResource::collection($first->users)
            ];
        }
        
        return Response::json($data);
    }
}
