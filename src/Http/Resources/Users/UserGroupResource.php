<?php

namespace FebriHidayan\Laratalk\Http\Resources\Users;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Laratalk;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'avatar' => Config::userGravatar()
                ? Laratalk::gravatar($this->email)
                : $this->{Config::userAvatar()},
            'name' => $this->name,
            'bio' => $this->{Config::userBio()},
            'role' => $this->role
        ];
    }
}
