<?php

namespace Kaw393939\Group\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'display_name' => $this->display_name,
            'description' => $this->description,
            'url' => route('groups.show',['group' => $this]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
