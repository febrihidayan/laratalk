<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Laratalk\Http\Resources\UserResource;
use Laratalk\Models\Message;

class UserController extends Controller
{
    public function __invoke($query)
    {
        $id = auth()->user()->id;

        $select = [
            'id',
            'name',
            ...array_keys(config('laratalk.users'))
        ];

        if ($query == 'all') {

            $users = Message::with(['userFrom', 'userTo'])
            ->where('laratalk_messages.to_id', $id)
                ->orWhere('laratalk_messages.from_id', $id)
                ->latest();

            $keys = [];

            foreach ($users->get() as $key => $user) {
                if ($user->userFrom->id == $id) {
                    $keys[$key] = $user->userTo->id;
                } else {
                    $keys[$key] = $user->userFrom->id;
                }
            }

            $keys = array_unique($keys);

            $ids = implode(',', $keys);

            $users = User::whereIn('id', $keys);

            if (!empty($key)) {
                $users = $users->orderByRaw(DB::raw("FIELD(id, $ids)"));
            }
        } else {
            $users = User::where('name', 'like', "%{$query}%")->where('id', '!=', $id);
        }

        $users = UserResource::collection($users->get($select));

        return response()->json($users);
    }
}
