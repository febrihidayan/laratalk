<?php

namespace FebriHidayan\Laratalk\Http\Resources;

use FebriHidayan\Laratalk\Laratalk;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNewChatResource extends JsonResource
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
            'avatar' => Laratalk::gravatar($this->email),
            'name' => $this->name
        ];
    }
}
