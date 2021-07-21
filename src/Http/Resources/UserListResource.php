<?php

namespace Laratalk\Http\Resources;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Laratalk\Laratalk;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'content' => $this->content ?? '',
            'content_by' => $this->by_id,
            'content_type' => $this->type,
            'chat_type' => $this->chat_type,
            'last_time' => Laratalk::lastTime($this->created_at),
            'read_count' => $this->readCount(),
            'status' => $this->statusMessage(),
        ];

        if ($this->group_id) {
            $userBy = User::find($this->by_id);
            $userTo = User::find($this->to_id);

            $data += [
                'avatar' => $this->group_avatar,
                'id' => $this->group_id,
                'name' => $this->group_name,
                'user_by_name' => $userBy->name,
                'content_to' => $userTo->id ?? '',
                'user_to_name' => $userTo->name ?? '',
            ];
        }
        else {
            $data += [
                'avatar' => Laratalk::gravatar($this->user_email),
                'id' => $this->user_id,
                'name' => $this->user_name,
            ];
        }

        return $data;
    }
}
