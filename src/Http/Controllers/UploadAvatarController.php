<?php

namespace FebriHidayan\Laratalk\Http\Controllers;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Events\Messages\SendEvent;
use FebriHidayan\Laratalk\Http\Resources\MessageResource;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\GroupUser;
use FebriHidayan\Laratalk\Models\Message;
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

        $isGroup = Request::get('user_type') === Message::TYPE_GROUP;

        $user = $isGroup
            ? Group::find(Request::get('user_id'))
            : Auth::user();

        $field = $isGroup
            ? 'avatar' : Config::userAvatar();

        Storage::delete(
            str_replace('/storage', 'public', $user->{$field})
        );

        $user->{$field} = $path;
        $user->save();

        $data = [
            'path' => $path
        ];

        if ($isGroup) {
            $message = Message::create([
                'by_id' => Auth::id(),
                'group_id' => $user->id,
                'type' => Message::AVATAR_GROUP
            ]);

            $messageResource = collect(new MessageResource($message))
                ->toArray();

            foreach (GroupUser::userAll($user->id) as $id) {

                SendEvent::dispatch(
                    $messageResource,
                    $id
                );

            }

            $data['data'] = $messageResource;
        }

        return Response::json($data);
    }
}
