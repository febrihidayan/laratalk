<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Groups;

use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\GroupUser;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StoreParticipantController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'group_id' => 'required',
            'users' => 'required|array|min:1'
        ]);

        if (!GroupUser::where([['user_id', Auth::id()], ['role', Group::ADMIN]])->exists()) {
            return Response::json([
                'message' => 'Admin only.'
            ], 401);
        }

        $group = Group::find(Request::get('group_id'));

        $group->users()->attach(Request::get('users'));

        $message = Message::create([
            'by_id' => Auth::id(),
            'group_id' => $group->id,
            'type' => Message::ADD_USER_GROUP
        ]);

        $message->users()
            ->attach(Request::get('users'));

        return Response::json([]);
    }
}
