<?php

namespace Laratalk\Http\Controllers\Users;

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Laratalk\Http\Resources\UserNewChatResource;

class NewChatController extends Controller
{
    public function __invoke()
    {
        $q = Request::get('q');

        $users = User::where('name', 'like', "%$q%")
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return Response::json(
            UserNewChatResource::collection($users)
        );
    }
}
