<?php

namespace FebriHidayan\Laratalk\Http\Resources\Messages;

use FebriHidayan\Laratalk\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ShowGroupResource extends JsonResource
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
            'content' => $this->content ?? '',
            'content_by' => $this->by_id,
            'content_type' => $this->type,
            'last_time' => $this->lastTime(),
            'time' => $this->time()
        ];

        if ($this->type === Message::CHAT && Auth::id() === $this->by_id) {
            $data += [
                'status' => $this->statusMessageRecipient(),
            ];
        }
        else {
            $data += [
                'user_by_name' => $this->user->name
            ];
        }

        $types = [
            Message::ADD_ADMIN_GROUP,
            Message::REMOVE_ADMIN_GROUP,
            Message::ADD_USER_GROUP,
            Message::REMOVE_USER_GROUP
        ];

        if (in_array($this->type, $types)) {
            $data += [
                'content_to' => $this->recipient->to_id,
                'user_to_name' => $this->recipient->user->name,
            ];
        }

        return $data;
    }
}
