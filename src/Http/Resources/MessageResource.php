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
            'content_by' => $this->from_id,
            'content_at' => $this->created_at
        ];

        if (Auth::id() === $this->from_id) {
            $data['status'] = $this->statusMessage();
        }

        return $data;
    }
}
