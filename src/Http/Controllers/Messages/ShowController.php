<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Messages;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Events\Messages\StatusEvent;
use FebriHidayan\Laratalk\Http\Resources\Messages\ShowGroupResource;
use FebriHidayan\Laratalk\Http\Resources\Messages\ShowUserResource;
use FebriHidayan\Laratalk\Http\Resources\Users\UserGroupResource;
use FebriHidayan\Laratalk\Laratalk;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\GroupUser;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $type = Request::get('type');

        if (
            (
                is_null($type) &&
                !in_array(
                    $type,
                    [
                        Message::TYPE_USER, Message::TYPE_GROUP
                    ]
                )
            ) ||
            (
                $type === Message::TYPE_GROUP &&
                !GroupUser::where([
                    ['user_id', Auth::id()],
                    ['group_id', $id]
                ])->exists()
            )
        ) {
            return Response::json([]);
        }

        if ($type === Message::TYPE_GROUP) {

            $messages = Message::where('group_id', $id)
                ->oldest()
                ->get();

            $messages = ShowGroupResource::collection($messages);

        }
        else {

            $messages = Message::whereNull('group_id')
                ->where( function($query) {
                    return $query->where('by_id', Auth::id())
                        ->orWhereHas('recipients', function($query) {
                            return $query->where('to_id', Auth::id());
                        });
                })
                ->where( function($query) use ($id) {
                    return $query->where('by_id', $id)
                        ->orWhereHas('recipients', function($query) use ($id) {
                            return $query->where('to_id', $id);
                        });
                })
                ->where( function($query) {
                    $query->where('delete_user_id', '!=', Auth::id())
                    ->orWhereNull('delete_user_id');
                })
                ->get();

            $messages = ShowUserResource::collection($messages);
            
        }

        $messageRecipient = Message::joinRecipient()
            ->when($type, function ($query, $type) use ($id) {

                if ($type === Message::TYPE_USER) {

                    return $query->whereMetaUser($id, true);
                }

                if ($type === Message::TYPE_GROUP) {

                    return $query
                        ->where(Config::messages('group_id'), $id)
                        ->where(Config::messageRecipient('to_id'), Auth::id());
                }
            })
            ->where(Config::messages('type'), Message::CHAT)
            ->whereNull(Config::messageRecipient('read_at'));

        if ($messages->count() && $messageRecipient->count()) {

            StatusEvent::dispatch([
                'id' => $messageRecipient->pluck('id'),
                'content_to' => Auth::id(),
                'status' => Message::READ,
                'chat_type' => $type
            ], $id);

            $messageRecipient->update(['read_at' => now()]);
        }

        $data = [
            'chat_type' => $type,
            'messages' => $messages
        ];

        if (Request::get('type') == Message::TYPE_USER) {
            $first = Config::userModel()::find($id);

            $data += [
                'id' => $first->id,
                'avatar' => Config::userGravatar()
                    ? Laratalk::gravatar($first->email)
                    : $first->{Config::userAvatar()},
                'name' => $first->name,
                'email' => $first->email,
                'bio' => $first->{Config::userBio()},
            ];
        } else {
            $first = Group::with('users')
                ->find($id);

            $data += [
                'id' => $first->id,
                'avatar' => $first->avatar ?? '',
                'name' => $first->name,
                'description' => $first->description ?? '',
                'last_time' => Laratalk::lastTime($first->created_at, true),
                'time' => $first-> created_at->format('H.i'),
                'users' => UserGroupResource::collection($first->users)
            ];
        }
        
        return Response::json($data);
    }
}
