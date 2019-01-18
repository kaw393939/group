<?php

namespace Kaw393939\Group\Models;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Contracts\LaratrustPermissionInterface;
use Laratrust\Traits\LaratrustPermissionTrait;
use Illuminate\Support\Facades\Config;

class Permission extends Model implements LaratrustPermissionInterface
{
    use LaratrustPermissionTrait;

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
        $this->table = Config::get('group.tables.permissions');
    }
}
