<?php
/**
 * Created by PhpStorm.
 * User: Monimus
 * Date: 1/18/2019
 * Time: 2:18 AM
 */

namespace Kaw393939\Group\http\Controllers;

use Illuminate\Http\Request;
use Kaw393939\Group\Http\Requests\GroupUserDestroyRequest;
use Kaw393939\Group\Http\Requests\GroupUserStoreRequest;
use Kaw393939\Group\Http\Requests\GroupUserUpdateRequest;
use Kaw393939\Group\Http\Resources\GroupUserResource as Resource;
use Kaw393939\Group\Http\Resources\GroupUsersCollection as Collection;
use Kaw393939\Group\Models\Group as Group;
use Kaw393939\Group\Models\Role;
use Kaw393939\Group\Models\User as User;

/**
 * Class GroupUsersController
 * @package Kaw393939\Group\http\Controllers
 */
class GroupUsersController
{
    /**
     * @param GroupUserStoreRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GroupUserStoreRequest $request, Group $group)
    {
        $user = User::where('email', $request->email)->first();
        $memberRole = Role::where('name', 'member')->first();
        $user->attachRole($memberRole, $group);
        return (new Resource($user))->response()->setStatusCode(201);
    }
    /**
     * @param GroupUserDestroyRequest $request
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(GroupUserDestroyRequest $request, Group $group, User $user)
    {
        $memberRole = Role::where('name', 'member')->first();
        $user->detachRoles([$memberRole], $group);
        return response()->json(null, 204);
    }
    /**
     * @param GroupUserUpdateRequest $request
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GroupUserUpdateRequest $request, Group $group, User $user)
    {
        if ($request->action == 'detach') {
            $user->detachRoles([$request->role], $group);

        } else {
            $user->attachRoles([$request->role], $group);
        };
        return response()->json(null, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new Collection(Group::all()))->response()->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Group $group, User $user)
    {
        return (new Resource($user))->response()->setStatusCode(200);
    }
}