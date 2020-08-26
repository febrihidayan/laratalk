<?php

namespace Laratalk\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laratalk\Models\Message;

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
        $count = Message::where([
            ['from_id', $this->from_id],
            ['to_id', $this->to_id],
        ])->whereNull('read_at')->count();

        $addField = [];

        foreach (array_keys(config('laratalk.users')) as $value) {
            $addField[$value] = $this->$value;
        }

        return [
            'id' => $this->userFrom->id,
            'name' => $this->userFrom->name,
            'avatar' => $this->userFrom->avatar,
            'from_id' => $this->from_id,
            'to_id' => $this->to_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'count' => $count,
            'field' => $addField
        ];
    }
}
