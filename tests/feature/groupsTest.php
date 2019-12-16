<?php

namespace Kaw393939\Group\Tests\Feature;

use Kaw393939\Group\Models\User as User;
use Kaw393939\Group\Tests\Helpers;

class GroupsTest extends \Kaw393939\Group\Tests\TestCase
{
    //use RefreshDatabase;

    public function testIndexGroups()
    {
        $response = $this->json('GET', route('groups.index'));
        $response->assertStatus(200);
    }

    public function testShowGroups()
    {
        $response = $this->json('GET', route('groups.show', ['group' => 1]));
        $response->assertStatus(200);
    }

    public function testStoreGroup()
    {
        $this
            ->actingAs(User::find(1))
            ->json(
                'POST',
                route('groups.store'),
                [
                    'name' => 'first-group',
                    'display_name' => 'display name',
                    'description' => 'example'
                ]
            )->assertStatus(201);
    }

    public function testStoreGroupAccessDenied()
    {
        $response = $this->actingAs(Helpers::createUser())->json(
            'POST',
            route('groups.store'),
            [
                'name' => 'first-group',
                'display_name' => 'display name',
                'description' => 'example'
            ]
        );
        $response->assertStatus(403);
    }

    // Extended store group tests
    public function testMemberCannotCreateGroup()
    {
        $member = Helpers::createUser();
        $response = $this->actingAs($member)->json(
            'POST',
            route('groups.store'),
            [
                'name' => 'first-group',
                'display_name' => 'display name',
                'description' => 'example'
            ]
        );
        $response->assertStatus(403);
    }
    public function testAdminCanCreateGroup()
    {
        $admin = Helpers::createUser($role = 'admin');
        $response = $this->actingAs($admin)->json(
            'POST',
            route('groups.store'),
            [
                'name' => 'first-group',
                'display_name' => 'display name',
                'description' => 'example'
            ]
        );
        $response->assertStatus(201);
    }
    public function testOwnerCanCreateGroup()
    {
        $owner = Helpers::createUser($role = 'owner');
        $response = $this->actingAs($owner)->json(
            'POST',
            route('groups.store'),
            [
                'name' => 'first-group',
                'display_name' => 'display name',
                'description' => 'example'
            ]
        );
        $response->assertStatus(201);
    }


    public function testUpdateGroup()
    {
        $response = $this->actingAs(User::find(1))->json(
            'PATCH',
            route('groups.update', ['group' => 1]),
            ['name' => 'system',
                'display_name' => 'System Users',
                'description' => 'A Different Description'
            ]
        );
        $response->assertStatus(200);
    }

    public function testUpdateGroupAccessDenied()
    {
        $response = $this->actingAs(Helpers::createUser())->json(
            'PATCH',
            route('groups.update', ['group' => 1]),
            ['name' => 'system',
                'display_name' => 'System People',
                'description' => 'example'
            ]
        );
        $response->assertStatus(403);
    }

    // Extended update group tests
    public function testMemberCannotUpdateGroup()
    {
        $member = Helpers::createUser();
        $response = $this->actingAs($member)->json(
            'PATCH',
            route('groups.update', ['group' => 1]),
            ['name' => 'system',
                'display_name' => 'System Users',
                'description' => 'A Different Description'
            ]
        );
        $response->assertStatus(403);
    }
    public function testAdminCanUpdateHisOwnGroup()
    {
        $admin = Helpers::createUser($role = 'admin');
        $group = Helpers::createGroupWithUserRole('admin', $admin);
        $response = $this->actingAs($admin)->json(
            'PATCH',
            route('groups.update', ['group' => $group->id]),
            ['name' => 'system',
                'display_name' => 'System Users',
                'description' => 'A Different Description'
            ]
        );
        $response->assertStatus(200);
    }
    public function testAdminCannotUpdateDifferentGroup()
    {
        $admin1 = Helpers::createUser($role = 'admin');
        $admin2 = Helpers::createUser($role = 'admin');
        $group1 = Helpers::createGroupWithUserRole('admin', $admin1);
        $group2 = Helpers::createGroupWithUserRole('admin', $admin2);
        $response = $this->actingAs($admin2)->json(
            'PATCH',
            route('groups.update', ['group' => $group1->id]),
            ['name' => 'system',
                'display_name' => 'System Users',
                'description' => 'A Different Description'
            ]
        );
        $response->assertStatus(403);
    }

    public function testDestroyGroup()
    {
        $group = Helpers::createGroupWithUserRole();
        $response = $this->actingAs(User::find(1))->json(
            'DELETE',
            route('groups.destroy', ['group' => $group->id])
        );
        $response->assertStatus(204);
    }

    public function testDestroyGroupAccessDenied()
    {
        $response = $this->actingAs(Helpers::createUser())->json(
            'DELETE',
            route('groups.destroy', ['group' => 1])
        );
        $response->assertStatus(403);
    }

    public function testMemberCannotDestroyGroup()
    {
        $member = Helpers::createUser();
        $response = $this->actingAs($member)->json(
            'DELETE',
            route('groups.destroy', ['group' => 1])
        );
        $response->assertStatus(403);
    }
    public function testAdminCannotDestroyGroup()
    {
        $admin = Helpers::createUser($role = 'admin');
        $group = Helpers::createGroupWithUserRole($role = 'admin', $admin);
        $response = $this->actingAs($admin)->json(
            'DELETE',
            route('groups.destroy', ['group' => $group->id])
        );
        $response->assertStatus(403);
    }
    public function testOnlyOwnerCanDestroyGroup()
    {
        $owner = Helpers::createUser($role = 'owner');
        $admin = Helpers::createUser($role = 'admin');
        $group = Helpers::createGroupWithUserRole($role = 'admin', $admin, $owner);
        $response = $this->actingAs($owner)->json(
            'DELETE',
            route('groups.destroy', ['group' => $group->id])
        );
        $response->assertStatus(204);
    }

    public function testMyGroups()
    {
        $response = $this->actingAs(User::find(1))
            ->json('GET', route('groups.mygroups'));
        $response->assertStatus(200);
    }
}
