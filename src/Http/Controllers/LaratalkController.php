<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laratalk\Events\Messages\StatusEvent;
use Laratalk\Laratalk;
use Laratalk\Models\Group;
use Laratalk\Models\Message;

class LaratalkController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $messages = Message::joinRecipient()
            ->where('laratalk_messages.type', Message::CHAT)
            ->where('laratalk_message_recipient.to_id', $user->id)
            ->whereNull('laratalk_message_recipient.accept_at');

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
                'title' => Config::get('laratalk.name'),
                'path' => Config::get('laratalk.path'),
                'profile' => [
                    'id' => $user->id,
                    'avatar' => Laratalk::gravatar($user->email),
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'models' => [
                    'group' => [
                        'admin' => Group::ADMIN,
                        'member' => Group::MEMBER
                    ],
                    'message' => [
                        'chat' => Message::CHAT,
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
                        'type_user' => Message::TYPE_USER,
                        'type_group' => Message::TYPE_GROUP,
                        'send' => Message::SEND,
                        'accept' => Message::ACCEPT,
                        'read' => Message::READ,
                    ]
                ],
                'translations' => Laratalk::availableTranslations(),
                'echo' => Config::get('broadcasting.connections.pusher')
            ]);
    }
}
