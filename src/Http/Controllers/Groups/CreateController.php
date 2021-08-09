<?php

namespace FebriHidayan\Laratalk\Http\Controllers\Groups;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Events\Groups\NewGroupEvent;
use FebriHidayan\Laratalk\Laratalk;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
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

        /**
         * add image validate if there is an image field
         */
        if (Request::file('image')) {
            
            $format = implode(',', Config::storageImageFormat());
            $size = Config::storageImageSize();

            $validate['image'] = "required|mimes:$format|max:$size";
        }

        Validator::validate(Request::all(), $validate);

        /**
         * If the image exists it will load the image upload process and
         * add an avatar field with the value of the image path.
         */
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

        // add owner group
        Request::merge([
            'user_id' => Auth::id()
        ]);

        // create group
        $group = Group::create(Request::all());

        // create relationship group to users pivot (group_user)
        $group->users()->attach(Auth::id(), [
            'role' => Group::ADMIN
        ]);

        // owner create group (message)
        Message::create([
            'by_id' => Auth::id(),
            'group_id' => $group->id,
            'type' => Message::CREATE_GROUP
        ]);

        // create participants group to users pivot (group_user)
        $usersId = collect(Request::get('users'))
            ->unique();
        
        // add participants (group)
        $group->users()->attach($usersId);

        // add participants (message)
        $messageCreate = Message::create([
            'by_id' => Auth::id(),
            'group_id' => $group->id,
            'type' => Message::ADD_USER_GROUP
        ]);

        // added participants id field (by_id)
        $messageCreate->users()
            ->sync($usersId);

        /**
         * Takes all the names of the group participants except
         * the one who added them.
         */
        $usersName = Config::userModel()::whereIn(
                'id',
                $usersId
            )
            ->get()
            ->pluck('name');

        $resourceEvent = [
            'avatar' => $group->avatar,
            'chat_type' => Message::TYPE_GROUP,
            'content' => '',
            'content_by' => $messageCreate->by_id,
            'content_type' => Message::ADD_USER_GROUP,
            'id' => $group->id,
            'last_time' => Laratalk::lastTime($messageCreate->created_at),
            'name' => $group->name,
            'read_count' => 0,
            'status' => Message::SEND,
            'user_by_name' => $messageCreate->user->name,
            'user_to_name' => $usersName,
        ];
            
        // send message new group
        foreach ($usersId as $id) {
            $resourceEvent['content_to'] = $id;

            NewGroupEvent::dispatch(
                $resourceEvent,
                $id
            );
        }

        return Response::json($resourceEvent);
    }
}
