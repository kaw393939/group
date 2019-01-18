<?php

namespace Kaw393939\Group\Models;


use Laratrust\Contracts\LaratrustRoleInterface;
use Laratrust\Traits\LaratrustRoleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Kaw393939\Group\Pivot\RolePivot;

class Role extends Model implements LaratrustRoleInterface
{
    use LaratrustRoleTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('group.tables.roles');
    }

}

