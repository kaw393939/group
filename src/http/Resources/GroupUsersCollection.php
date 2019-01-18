<?php
/**
 * Created by PhpStorm.
 * User: Monimus
 * Date: 1/18/2019
 * Time: 3:18 AM
 */

namespace Kaw393939\Group\http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Kaw393939\Group\Http\Resources\GroupUserResource as Resource;

class GroupUsersCollection extends ResourceCollection
{
    public $collects = Resource::class;

    public function toArray($request)
    {

        return [
            'data' => $this->collection,
            'links' => [
                'url' => route('groups.mygroups')
            ]
        ];

    }
}