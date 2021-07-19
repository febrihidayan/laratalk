<?php

namespace Laratalk\Http\Resources;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Laratalk\Laratalk;

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
            'chat_type' => $this->chat_type,
            'last_time' => Laratalk::lastTime($this->created_at, true),
            'time' => $this->created_at->format('H.i')
        ];

        if ($this->group_id) {
            $userBy = User::find($this->by_id);
            $userTo = User::find($this->to_id);

            $data += [
                'group_id' => $this->group_id,
                'content_type' => $this->type,
                'user_by' => [
                    'id' => $userBy->id,
                    'name' => $userBy->name,
                ],
                'user_to' => [
                    'id' => $userTo->id ?? '',
                    'name' => $userTo->name ?? '',
                ]
            ];
        }

        if (Auth::id() === $this->by_id) {
            $data['status'] = $this->statusMessage();
        }

        return $data;
    }
}
