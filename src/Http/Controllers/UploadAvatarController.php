<?php

namespace FebriHidayan\Laratalk\Http\Controllers;

use FebriHidayan\Laratalk\Config;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadAvatarController extends Controller
{
    public function __invoke()
    {
        $format = implode(',', Config::storageImageFormat());
        $size = Config::storageImageSize();
        
        Validator::validate(Request::all(), [
            'image' => 
                "required|mimes:$format|max:$size"
        ]);

        $path = Storage::putFile(
            Config::storagePath(),
            Request::file('image')
        );

        $path = str_replace('public', '/storage', $path);

        $user = Auth::user();

        Storage::delete(
            str_replace('/storage', 'public', $user->avatar)
        );

        $user->avatar = $path;
        $user->save();

        return Response::json(
            $path
        );
    }
}
