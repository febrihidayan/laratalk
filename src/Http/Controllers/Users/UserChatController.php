<?php

namespace Laratalk\Http\Controllers\Users;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Laratalk\Http\Resources\UserListResource;
use Laratalk\Models\Message;

class UserChatController extends Controller
{
    public function __invoke()
    {
        $q = Request::get('q');

        $users = Message::select(
            [
                'laratalk_messages.*',
                'laratalk_message_recipient.*',
                'laratalk_groups.name as group_name',
                'laratalk_groups.avatar as group_avatar',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
            ])
            ->joinRecipientUserOrMessageUser()
            ->joinGroup()
            ->when($q, function($query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->where( function($query) {
                return $query
                    ->whereNull('laratalk_message_recipient.message_id')
                    ->orWhere( function($query) {
                        return $query
                            ->where('users.id', '!=', Auth::id())
                            ->whereNotNull('laratalk_message_recipient.message_id');
                    });
            })
            // ->where(function ($q) {
            //     $q->where('laratalk_messages.by_id', Auth::id())
            //         ->orWhere('laratalk_message_recipient.to_id', Auth::id());
            // })

            /**
             * If the type is 11 and 12 then only the designated user sees the message
             * 
             * type 11: Message::ADD_ADMIN_GROUP
             * type 12: Message::REMOVE_ADMIN_GROUP
             */
            ->where( function($query) {
                $dataWhere = [
                    Message::ADD_ADMIN_GROUP,
                    Message::REMOVE_ADMIN_GROUP
                ];
                return $query
                    ->whereNotIn(
                        'laratalk_messages.type',
                        $dataWhere
                    )
                    ->orWhere( function($query) use($dataWhere) {
                        return $query
                            ->whereIn(
                                'laratalk_messages.type',
                                $dataWhere
                            )
                            ->where('laratalk_message_recipient.to_id', Auth::id());
                    });
            })
            ->latest('laratalk_messages.created_at')
            ->get();

        $resourceUsers = [];
        
        foreach ($users as $user) {
            $collect = collect($resourceUsers);

            if (
                ($user->chat_type === Message::TYPE_GROUP && !$collect->where('group_id', $user->group_id)->count())
                ||
                ($user->chat_type === Message::TYPE_USER && !$collect->where('group_id', null)->where('user_id', $user->user_id)->count())
            ) {
                $resourceUsers[] = $user;
            }

        }
        
        return Response::json(
            UserListResource::collection($resourceUsers)
        );

    }
}
