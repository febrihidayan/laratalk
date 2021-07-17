<?php

namespace Laratalk\Http\Controllers\Users;

use Illuminate\Foundation\Auth\User;
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

        $users = Message::joinUser()->authUser()
            ->latest('laratalk_messages.created_at')
            ->where('users.id', '!=', Auth::id())
            ->when($q, function($query, $q) {
                return $query->where('name', 'like', "%{$q}%");
            })
            ->get([
                'laratalk_messages.*',
                'laratalk_message_recipient.*',
                'users.id',
                'users.name',
                'users.email',
            ])
            ->unique('id');

        return Response::json(
            UserListResource::collection($users)
        );

    }
}
