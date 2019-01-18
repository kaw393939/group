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

    public function testMyGroups()
    {
        $response = $this->actingAs(User::find(1))
            ->json('GET', route('groups.mygroups'));
        $response->assertStatus(200);
    }
}
