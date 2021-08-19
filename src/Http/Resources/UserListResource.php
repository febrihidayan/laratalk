<?php

namespace FebriHidayan\Laratalk\Http\Resources;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Laratalk;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'avatar' => $this->group_avatar ?? '',
                'id' => $this->group_id,
                'name' => $this->group_name,
                'user_by_name' => $userBy->name,
                'content_to' => $userTo->id ?? '',
                'user_to_name' => $userTo->name ?? '',
            ];
        }
        else {
            $data += [
                'avatar' => Config::userGravatar()
                    ? Laratalk::gravatar($this->user_email)
                    : $this->{Config::userAvatar()},
                'id' => $this->user_id,
                'name' => $this->user_name,
            ];
        }

        return $data;
    }
}
