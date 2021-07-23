<?php

namespace Laratalk\Http\Controllers\Groups;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Laratalk\Models\Group;
use Laratalk\Models\GroupUser;

class CreateController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'name' => 'required',
            'users' => 'required:array'
        ]);

        Request::merge([
            'user_id' => Auth::id()
        ]);

        $group = Group::create(Request::all());

        GroupUser::create([
            'user_id' => Auth::id(),
            'group_id' => $group->id,
            'role' => 1
        ]);

        $group->users()->sync(Request::get('users'));

        return Response::json($group);
    }
}
