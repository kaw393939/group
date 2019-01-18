<?php

namespace Kaw393939\Group\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupUserResource extends JsonResource
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
            'name' => $this->name,
            'url' => route('users.index',['group' => $this]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
