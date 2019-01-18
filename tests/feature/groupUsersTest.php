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
