<?php
/**
 * Created by PhpStorm.
 * User: Monimus
 * Date: 1/17/2019
 * Time: 11:31 PM
 */

namespace Kaw393939\Group\Pivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Kaw393939\Group\Models\Role;

class RolePivot extends Pivot
{
    public function role() {
        return $this->hasOne(Role::Class,'id','role_id');

    }
}


