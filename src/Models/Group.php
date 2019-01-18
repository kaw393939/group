<?php

namespace Kaw393939\Group\Models;

/**
 * This file is part of Laratrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Laratrust
 */

use Laratrust\Contracts\LaratrustTeamInterface;
use Laratrust\Traits\LaratrustTeamTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Group extends Model implements LaratrustTeamInterface
{
    use LaratrustTeamTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;
    protected $fillable = ['name','display_name','description'];

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('group.tables.teams');
    }

}
