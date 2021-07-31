<?php

namespace Laratalk\Http\Resources\Messages;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ShowUserResource extends JsonResource
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

        if (Auth::id() === $this->by_id) {
            $data['status'] = $this->statusMessageRecipient();
        }

        return $data;
    }
}
