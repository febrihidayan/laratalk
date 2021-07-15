<?php

namespace Laratalk\Http\Resources;

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
        return [
            'id' => $this->id,
            'avatar' => Laratalk::getUserAvatar($this->email),
            'name' => $this->name,
            'content' => $this->content ?? '',
            'content_by' => $this->from_id,
            'read_count' => $this->readCount(),
            'status' => $this->statusMessage(),
            'content_at' => $this->created_at
        ];
    }
}
