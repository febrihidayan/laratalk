<?php

namespace Laratalk\Http\Controllers\Users;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Laratalk\Laratalk;

class ChangeLanguageController extends Controller
{
    public function __invoke()
    {
        $inLocale = implode(',', array_keys(Laratalk::$languages));

        Validator::validate(Request::all(), [
            'locale' => "required|in:$inLocale"
        ]);

        $user = Auth::user();
        $user->locale = Request::get('locale');
        $user->save();

        return Response::json(
            Laratalk::availableTranslations(Request::get('locale'))
        );
    }
}
