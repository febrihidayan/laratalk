<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Groups;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Http\Resources\UserNewChatResource;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\GroupUser;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ChangeAdminController extends Controller
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

        $groupUser = GroupUser::where([
                ['user_id', Request::get('user_id')],
                ['group_id', Request::get('group_id')]
            ])
            ->first();

        GroupUser::where([
            ['user_id', Request::get('user_id')],
            ['group_id', Request::get('group_id')]
        ])->update([
            'role' => $groupUser->role === Group::ADMIN
                ? Group::MEMBER : Group::ADMIN
        ]);

        $message = Message::create([
            'by_id' => Auth::id(),
            'group_id' => Request::get('group_id'),
            'type' => $groupUser->role !== Group::ADMIN
                ? Message::ADD_ADMIN_GROUP : Message::REMOVE_ADMIN_GROUP
        ]);

        $message->users()->attach([Request::get('user_id')]);

        return Response::json([]);
    }
}
