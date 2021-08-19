<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Groups;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Http\Resources\UserNewChatResource;
use FebriHidayan\Laratalk\Models\GroupUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'group_id' => 'required'
        ]);

        $idUsers = GroupUser::where('group_id', Request::get('group_id'))
            ->pluck('user_id');

        $q = Request::get('q');

        $users = Config::userModel()::whereNotIn('id', $idUsers)
            ->where('name', 'like', "%$q%")
            ->get();

        return Response::json(
            UserNewChatResource::collection($users)
        );
    }
}
