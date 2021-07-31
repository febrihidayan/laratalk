<?php

namespace Laratalk\Http\Controllers\Messages;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Laratalk\Events\Messages\SendEvent;
use Laratalk\Http\Resources\MessageResource;
use Laratalk\Models\GroupUser;
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

        if (Request::get('chat_type') === Message::TYPE_GROUP) {
            GroupUser::userGroup(Request::get('id'))->delete();

            $message = Message::create([
                'by_id' => Auth::id(),
                'group_id' => Request::get('id'),
                'type' => Message::LEAVE_GROUP
            ]);

            $userGroups = GroupUser::where('group_id', Request::get('id'))
                ->get()
                ->pluck('user_id');

            $messageResource = collect(
                new MessageResource(
                    Message::find($message->id)
                )
            )->toArray();
            
            foreach ($userGroups as $id) {

                SendEvent::dispatch(
                    $messageResource,
                    $id
                );
                
            }

        }
        else {
            Message::whereHas('recipient', function ($query) {
                    return $query->where('to_id', Request::get('id'))
                        ->orWhere('to_id', Auth::id());
                })
                ->whereNotNull('delete_user_id')
                ->where(function ($query) {
                    $query->where('by_id', Auth::id())
                    ->orWhere('by_id', Request::get('id'));
                })
                ->delete();

            Message::whereHas('recipient',function($query) {
                    return $query->where('to_id', Request::get('id'))
                        ->orWhere('to_id', Auth::id());
                })
                ->whereNull('delete_user_id')
                ->where( function($query) {
                    $query->where('by_id', Auth::id())
                        ->orWhere('by_id', Request::get('id'));
                })
                ->update([
                    'delete_user_id' => Auth::id()
                ]);
        }

        return Response::json([]);
    }
}
