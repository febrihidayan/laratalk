<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Users;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Http\Resources\UserListResource;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class UserChatController extends Controller
{
    public function __invoke()
    {
        $q = Request::get('q');

        $users = Message::select(
            [
                Config::messages('*'),
                Config::messageRecipient('*'),
                Config::groups('name as group_name'),
                Config::groups('avatar as group_avatar'),
                Config::users('id'),
                Config::users('id as user_id'),
                Config::users('name as user_name'),
                Config::users('email as user_email'),
            ])
            ->joinRecipientUserOrMessageUser()
            ->joinGroup()
            ->joinGroupUser()
            ->where(Config::users('id'), '!=', Auth::id())

            // search user chat
            ->when($q, function($query, $q) {
                return $query->where(Config::users('name'), 'like', "%{$q}%")
                    ->orWhere(Config::groups('id'), 'like', "%{$q}%");
            })
            
            // check if the user is still a member of the group
            ->where( function($query) {
                $query->where( function($query) {
                        $query->whereNull(Config::messages('group_id'));
                    })
                    ->orWhere( function($query) {
                        $query->whereNotNull(Config::messages('group_id'))
                            ->where(Config::groupUser('user_id'), Auth::id());
                    });
            })

            // feature delete chat (private)
            ->where( function($q) {
                $q->where(Config::messages('delete_user_id'), '!=', Auth::id())
                    ->orWhereNull(Config::messages('delete_user_id'));
            })

            // retrieve messages from the recipient (to_id) or yourself (by_id)
            ->where(function ($q) {
                $q->where(Config::messages('by_id'), Auth::id())
                    ->orWhere(Config::messageRecipient('to_id'), Auth::id());
            })

            /**
             * If the type is 11 and 12 then only the designated user sees
             * the message.
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
                        Config::messages('type'),
                        $dataWhere
                    )
                    ->orWhere( function($query) use($dataWhere) {
                        return $query
                            ->whereIn(
                                Config::messages('type'),
                                $dataWhere
                            )
                            ->where(Config::messageRecipient('to_id'), Auth::id());
                    });
            })
            ->latest(Config::messages('created_at'))
            ->get();

        $resourceUsers = [];
        
        foreach ($users as $user) {
            $collect = collect($resourceUsers);

            if (
                ($user->chatType() === Message::TYPE_GROUP && !$collect->where('group_id', $user->group_id)->count())
                ||
                ($user->chatType() === Message::TYPE_USER && !$collect->where('group_id', null)->where('user_id', $user->user_id)->count())
            ) {
                $resourceUsers[] = $user;
            }

        }
        
        return Response::json(
            UserListResource::collection($resourceUsers)
        );

    }
}
