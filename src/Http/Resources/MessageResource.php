<?php

namespace Laratalk\Http\Resources;

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
            'content_by' => $this->from_id,
            'last_time' => Laratalk::lastTime($this->created_at, true),
            'time' => $this->created_at->format('H.i')
        ];

        if (Auth::id() === $this->from_id) {
            $data['status'] = $this->statusMessage();
        }

        return $data;
    }
}
