<?php

namespace Laratalk\Http\Controllers\Users;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ChangeDarkmodeController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'darkmode' => "required|boolean"
        ]);

        $user = Auth::user();
        $user->dark_mode = Request::get('darkmode');
        $user->save();

        return Response::json(
            Request::get('darkmode')
        );
    }
}
