<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Groups;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Http\Resources\UserNewChatResource;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\GroupUser;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RemoveParticipantController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'group_id' => 'required',
            'user_id' => 'required'
        ]);

        if (!GroupUser::where([['user_id', Auth::id()], ['role', Group::ADMIN]])->exists()) {
            return Response::json([
                'message' => 'Admin only.'
            ], 401);
        }

        GroupUser::where([
                ['user_id', Request::get('user_id')],
                ['group_id', Request::get('group_id')]
            ])
            ->delete();

        $message = Message::create([
            'by_id' => Auth::id(),
            'group_id' => Request::get('group_id'),
            'type' => Message::REMOVE_USER_GROUP
        ]);

        $message->users()->attach([Request::get('user_id')]);

        return Response::json([]);
    }
}
