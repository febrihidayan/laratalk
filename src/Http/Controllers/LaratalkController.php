<?php

namespace FebriHidayan\Laratalk\Http\Controllers;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Events\Messages\StatusEvent;
use FebriHidayan\Laratalk\Laratalk;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LaratalkController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $messages = Message::joinRecipient()
            ->where(Config::messages('type'), Message::CHAT)
            ->where(Config::messageRecipient('to_id'), $user->id)
            ->whereNull(Config::messageRecipient('accept_at'));

        if ($messages->count()) {

            foreach ($messages->get()->unique('by_id') as $item) {

                $idMessages = $messages->get()
                    ->where('by_id', $item->by_id)
                    ->pluck('id');

                StatusEvent::dispatch(
                    [
                        'id' => $idMessages,
                        'chat_type' => $item->chatType(),
                        'content_to' => $item->to_id,
                        'status' => Message::ACCEPT
                    ],
                    $item->by_id
                );
                
            }

            $messages->update([
                'accept_at' => now()
            ]);

        }

        return view('laratalk::layout')
            ->withScripts([
                'title' => Config::name(),
                'path' => Config::path(),
                'profile' => [
                    'id' => $user->id,
                    'avatar' => Config::userGravatar()
                        ? Laratalk::gravatar($user->email)
                        : $user->{Config::avatar()},
                    'name' => $user->name,
                    'email' => $user->email,
                    'locale' => $user->{Config::locale()},
                    'dark_mode' => (bool)$user->{Config::darkMode()}
                ],
                'languages' => Laratalk::$languages,
                'models' => [
                    'group' => [
                        'admin' => Group::ADMIN,
                        'member' => Group::MEMBER
                    ],
                    'message' => [
                        'chat' => Message::CHAT,
                        'pull_message' => Message::PULL_MESSAGE,
                        'create_group' => Message::CREATE_GROUP,
                        'avatar_group' => Message::AVATAR_GROUP,
                        'rename_group' => Message::RENAME_GROUP,
                        'description_group' => Message::DESCRIPTION_GROUP,
                        'info_all_group' => Message::INFO_ALL_GROUP,
                        'info_admin_group' => Message::INFO_ADMIN_GROUP,
                        'chat_all_group' => Message::CHAT_ALL_GROUP,
                        'chat_admin_group' => Message::CHAT_ADMIN_GROUP,
                        'add_user_group' => Message::ADD_USER_GROUP,
                        'remove_user_group' => Message::REMOVE_USER_GROUP,
                        'add_admin_group' => Message::ADD_ADMIN_GROUP,
                        'remove_admin_group' => Message::REMOVE_ADMIN_GROUP,
                        'leave_group' => Message::LEAVE_GROUP,
                        'type_user' => Message::TYPE_USER,
                        'type_group' => Message::TYPE_GROUP,
                        'send' => Message::SEND,
                        'accept' => Message::ACCEPT,
                        'read' => Message::READ,
                    ]
                ],
                'translations' => Laratalk::availableTranslations(
                    $user->{Config::locale()}
                ),
                'echo' => Config::pusher()
            ]);
    }
}
