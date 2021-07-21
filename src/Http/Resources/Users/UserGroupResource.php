<?php

namespace Laratalk\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Laratalk\Laratalk;

class UserGroupResource extends JsonResource
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
            'name' => $this->name,
            'role' => $this->role
        ];
    }
}
