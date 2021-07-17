<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Laratalk\Http\Resources\UserListResource;
use Laratalk\Models\Message;

class UserController extends Controller
{
    public function __invoke($query)
    {
        if ($query == 'all') {

            $users = Message::joinUser()->authUser()
                ->latest('laratalk_messages.created_at')
                ->where('users.id', '!=', Auth::id())
                ->get([
                    'laratalk_messages.*',
                    'laratalk_message_recipient.*',
                    'users.id',
                    'users.name',
                    'users.email',
                ])
                ->unique('id');
            
        } else {

            $users = User::where('name', 'like', "%{$query}%")->where('id', '!=', $id)->get();

        }

        return Response::json(
            UserListResource::collection($users)
        );

    }
}
