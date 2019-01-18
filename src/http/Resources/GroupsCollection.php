<?php

namespace Kaw393939\Group\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Kaw393939\Group\Http\Resources\GroupResource as Resource;

class GroupsCollection extends ResourceCollection
{
    public $collects = Resource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'data' => $this->collection,
            'links' => [
                'self' => route('groups.index'),
            ],
        ];

    }
}
