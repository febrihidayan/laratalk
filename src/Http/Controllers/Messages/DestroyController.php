<?php

namespace Laratalk\Http\Controllers\Messages;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Laratalk\Laratalk;
use Laratalk\Models\Group;
use Laratalk\Models\Message;

class DestroyController extends Controller
{
    public function __invoke()
    {
        Validator::validate(Request::all(), [
            'id' => 'required',
            'chat_type' => sprintf(
                'required|in:%s,%s',
                Message::TYPE_GROUP,
                Message::TYPE_USER
            )
        ]);
    }
}
