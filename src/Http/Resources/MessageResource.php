<?php

namespace Laratalk\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
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
            'id' => $this->id,
            'content' => $this->content,
            'content_by' => $this->by_id,
            'content_type' => $this->type,
            'chat_type' => $this->chatType(),
            'last_time' => $this->lastTime(),
            'time' => $this->time()
        ];

        if ($this->group_id) {
            $data += [
                'group_id' => $this->group_id,
                'content_to' => $this->recipient->to_id ?? '',
                'user_by_name' => $this->user->name,
                'user_to_name' => $this->recipient->user->name ?? ''
            ];
        }

        if (Auth::id() === $this->by_id) {
            $data['status'] = $this->statusMessage();
        }

        return $data;
    }
}
