<?php

namespace Laratalk\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laratalk\Config;
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
            'chat_type' => $this->chatType(),
            'last_time' => Laratalk::lastTime($this->created_at),
            'read_count' => $this->readCount(),
            'status' => $this->statusMessage(),
        ];

        if ($this->group_id) {
            $userBy = Config::userModel()::find($this->by_id);
            $userTo = Config::userModel()::find($this->to_id);

            $data += [
                'avatar' => $this->group_avatar ?? Laratalk::gravatar(''),
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
