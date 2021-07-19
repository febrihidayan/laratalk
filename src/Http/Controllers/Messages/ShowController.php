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

        $messages = Message::select([
                'laratalk_messages.*',
                'laratalk_message_recipient.*',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                ...$select ?? []
            ])
            ->leftJoin(
                'laratalk_message_recipient',
                'laratalk_messages.id',
                '=',
                'laratalk_message_recipient.message_id'
            )
            ->leftJoin(
                'users',
                'laratalk_message_recipient.to_id',
                '=',
                'users.id'
            )
            ->when($type, function($query, $type) use ($id) {
                if ($type === Message::TYPE_USER) {
                    return $query
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
                        ->where('laratalk_groups.id', $id)
                        ->whereNotNull('laratalk_messages.group_id');
                }
            })
            ->oldest('laratalk_messages.created_at')
            ->get();
        
        $messageMeta = Message::joinRecipient()
            ->where('type', Message::CHAT)
            ->whereMetaUser($id, true)
            ->whereNull('laratalk_message_recipient.read_at');

        if ($messages->count() && $messageMeta->count()) {

            StatusEvent::dispatch([
                'id' => $messageMeta->pluck('id'),
                'content_to' => Auth::id(),
                'status' => Message::READ
            ], $id);

            $messageMeta->update(['read_at' => now()]);
                
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
