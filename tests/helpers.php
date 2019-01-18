<?php

namespace Kaw393939\Group\Tests;
use Kaw393939\Group\Models\User as User;
use Kaw393939\Group\Models\Group as Group;
use Kaw393939\Group\Models\Role as Role;

/**
 * Class helpers
 * @package Kaw393939\Group\Tests
 */
class helpers {

    /**
     * @param string $role
     * @return mixed
     */
    public static function createUser($role = 'member')
    {
        $user = factory(User::class)->create();
        $memberRole = Role::where('name', $role)->first();
        $group = Group::find(1);
        $user->attachRole($memberRole, $group);
        return $user;
    }

    /**
     * @param string $role
     * @param User|NULL $member
     * @param User|null $owner
     * @return mixed
     */
    public static function createGroupWithUserRole($role = 'member', User $member = NULL, User $owner = null)
    {
        if (is_null($member)) {
            $member = Helpers::createUser();
        }

        if (is_null($owner)) {
            $owner = User::find(1);
        }
        $role = Role::where('name', $role)->first();
        $ownerRole = Role::where('name', 'owner')->first();

        $group = Group::create(
            [
            'name' => 'test-group',
            'display_name' => 'display name',
            'description' => 'example'
            ]
        );

        $owner->attachRole($ownerRole, $group);
        $member->attachRole($role, $group);
        return $group;
    }

}