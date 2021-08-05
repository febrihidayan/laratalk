<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Groups;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Events\Groups\NewGroupEvent;
use FebriHidayan\Laratalk\Http\Resources\UserListResource;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\GroupUser;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CreateController extends Controller
{
    public function __invoke()
    {
        $validate = [
            'name' => 'required',
            'users' => 'required:array',
            'users.*' => 'numeric'
        ];

        if (Request::file('image')) {
            
            $format = implode(',', Config::storageImageFormat());
            $size = Config::storageImageSize();

            $validate['image'] = "required|mimes:$format|max:$size";
        }

        Validator::validate(Request::all(), $validate);

        if (Request::file('image')) {
            $path = Storage::putFile(
                    Config::storagePath(),
                    Request::file('image')
                );

            Request::merge([
                'avatar' =>
                    str_replace('public', '/storage', $path)
            ]);

        }

        Request::merge([
            'user_id' => Auth::id()
        ]);

        $group = Group::create(Request::all());

        $users = collect(Request::get('users'))
            ->prepend(Auth::id())
            ->unique();

        foreach ($users as $id) {

            GroupUser::create([
                'user_id' => $id,
                'group_id' => $group->id,
                'role' => Auth::id() === $id
                    ? Group::ADMIN : Group::MEMBER
            ]);

            $messageCreate = Message::create([
                'by_id' => Auth::id(),
                'group_id' => $group->id,
                'type' => Auth::id() === $id
                    ? Message::CREATE_GROUP
                    : Message::ADD_USER_GROUP
            ]);

            if (Auth::id() !== $id) {
                $messageCreate->recipient()->create([
                    'to_id' => $id
                ]);
            }

            $messageCreate = new UserListResource(
                $messageCreate->select([
                        '*',
                        Config::groups('avatar as group_avatar'),
                        Config::groups('name as group_name'),
                    ])
                    ->joinGroup()
                    ->joinRecipient()
                    ->first()
            );

            event( new NewGroupEvent(
                collect($messageCreate)->toArray(),
                $id
            ));
        }

        return Response::json($group);
    }
}
