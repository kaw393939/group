<?php

namespace Kaw393939\Group\Tests\Feature;

use Kaw393939\Group\Models\Group as Group;
use Kaw393939\Group\Models\User as User;
use Kaw393939\Group\Tests\Helpers;

class GroupsUsersTest extends \Kaw393939\Group\Tests\TestCase
{
    //use RefreshDatabase;

    public function testStoreGroupUser()
    {
        $user = Helpers::createUser();
        $group = Group::find(1);
        $response = $this->actingAs(User::find(1))->json(
            'POST',
            route('users.store', ['group' => $group->id]),
            [
                'email' => $user->email
            ]
        );
        $response->assertStatus(201);
    }

    public function testStoreGroupUserAccessDenied()
    {
        $user = Helpers::createUser();
        $group = Group::find(1);
        $response = $this->actingAs($user)->json(
            'POST',
            route('users.store', ['group' => $group->id]),
            [
                'email' => $user->email
            ]
        );
        $response->assertStatus(403);
    }

    // Extended store-group-user Permission tests
    public function testOwnerCanCreateAdminGroupUser()
    {
        $admin = Helpers::createUser($role = 'admin');
        $owner = Helpers::createUser($role = 'owner');
        $group = Group::find(1);
        $response = $this->actingAs($owner)->json(
            'POST',
            route('users.store', ['group' => $group->id]),
            [
                'email' => $admin->email
            ]
        );
        $response->assertStatus(201);
    }
//    public function testAdminCannotCreateOwnerGroupUser()
//
//    {
//        $admin = Helpers::createUser($role = 'admin');
//        $owner = Helpers::createUser($role = 'owner');
//        $group = Group::find(1);
//        $response = $this->actingAs($admin)->json(
//            'POST',
//            route('users.store', ['group' => $group->id]),
//            [
//                'email' => $owner->email
//            ]
//        );
//        $response->assertStatus(403);
//    }
    public function testMemberCannotCreateAdminGroupUser()
    {
        $member = Helpers::createUser();
        $admin = Helpers::createUser($role = 'admin');
        $group = Group::find(1);
        $response = $this->actingAs($member)->json(
            'POST',
            route('users.store', ['group' => $group->id]),
            [
                'email' => $admin->email
            ]
        );
        $response->assertStatus(403);
    }
    public function testMemberCannotCreateOwnerGroupUser()
    {
        $member = Helpers::createUser();
        $admin = Helpers::createUser($role = 'admin');
        $group = Group::find(1);
        $response = $this->actingAs($member)->json(
            'POST',
            route('users.store', ['group' => $group->id]),
            [
                'email' => $admin->email
            ]
        );
        $response->assertStatus(403);
    }


    public function testDestroyGroupUser()
    {
        $user = Helpers::createUser();
        $group = Helpers::createGroupWithUserRole('member', $user);
        $response = $this->actingAs(User::find(1))->json(
            'DELETE',
            route('users.destroy', ['group' => $group->id, $user->id]));
        $response->assertStatus(204);
    }

    public function testDestroyGroupUserAccessDenied()
    {
        $user = Helpers::createUser();
        $nonmember = Helpers::createUser();

        $group = Helpers::createGroupWithUserRole('member', null, $user);
        $response = $this->actingAs($nonmember)->json(
            'DELETE',
            route('users.destroy', ['group' => $group->id, $user->id]));
        $response->assertStatus(403);
    }

    // Extended destroy-group-user permission tests
    public function testMemberCannotDestroyGroupUser()
    {
        $group_user = Helpers::createUser();
        $member_user = Helpers::createUser();
        $group = Helpers::createGroupWithUserRole('member', $group_user);
        $response = $this->actingAs($member_user)->json(
            'DELETE',
            route('users.destroy', ['group' => $group->id, $group_user->id]));
        $response->assertStatus(403);
    }
    public function testAdminCanDestroyOnlyOwnGroupUser()
    {
        $admin_user = Helpers::createUser($role = 'admin');
        $group = Helpers::createGroupWithUserRole('admin', $admin_user);
        $response = $this->actingAs($admin_user)->json(
            'DELETE',
            route('users.destroy', ['group' => $group->id, $admin_user->id]));
        $response->assertStatus(204);
    }
    public function testAdminCannotDestroyRandomGroupsUser()
    {
        $admin1 = Helpers::createUser($role = 'admin');
        $admin2 = Helpers::createUser($role = 'admin');
        $group = Helpers::createGroupWithUserRole('admin', $admin1);
        $response = $this->actingAs($admin2)->json(
            'DELETE',
            route('users.destroy', ['group' => $group->id, $admin1->id]));
        $response->assertStatus(403);
    }
//    public function testAdminCannotDestroyOwner()
//
//    {
//        $admin = Helpers::createUser($role = 'admin');
//        $owner = Helpers::createUser($role = 'owner');
//        $group = Helpers::createGroupWithUserRole('admin', $admin, $owner);
//        $response = $this->actingAs($admin)->json(
//            'DELETE',
//            route('users.destroy', ['group' => $group->id, $owner->id]));
//        $response->assertStatus(403);
//    }
    public function testOwnerCanDestroyAnyGroupUser()
    {
        $admin = Helpers::createUser($role = 'admin');
        $owner = Helpers::createUser($role = 'owner');
        $group = Helpers::createGroupWithUserRole('admin', $admin, $owner);
        $response = $this->actingAs($owner)->json(
            'DELETE',
            route('users.destroy', ['group' => $group->id, $admin->id]));
        $response->assertStatus(204);
    }


    public function testIndexGroupUser()
    {

        $response = $this->actingAs(User::find(1))->json('GET', route('users.index', ['group' => '1']));
        $response->assertStatus(200);
    }

    public function testShowGroupUser()
    {
        $response = $this->actingAs(User::find(1))->json('GET', route('users.show', ['group' => '1', 'user' => '1']));
        $response->assertStatus(200);
    }

    public function testUpdateGroupUser()
    {
        $user = Helpers::createUser();
        $group = Helpers::createGroupWithUserRole('member', $user);
        $response = $this->actingAs(User::find(1))->json(
            'PATCH',
            route('users.update', ['group' => $group->id, $user->id]),
            [
                'action' => 'attach',
                'role' => 'admin'
            ]
        );
        $response->assertStatus(200);
    }

    public function testUpdateGroupUserAccessDenied()
    {
        $user = Helpers::createUser();
        $nonmember = Helpers::createUser();

        $group = Helpers::createGroupWithUserRole('member', $user);
        $response = $this->actingAs($nonmember)->json(
            'PATCH',
            route('users.update', ['group' => $group->id, $user->id]),
            [
                'action' => 'attach',
                'role' => 'admin'
            ]
        );
        $response->assertStatus(403);
    }

}
