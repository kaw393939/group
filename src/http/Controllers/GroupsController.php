<?php

namespace Kaw393939\Group\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kaw393939\Group\Http\Requests\GroupDestroyRequest;
use Kaw393939\Group\Http\Requests\GroupStoreRequest;
use Kaw393939\Group\Http\Requests\GroupUpdateRequest;
use Kaw393939\Group\Http\Resources\GroupResource as Resource;
use Kaw393939\Group\Http\Resources\GroupsCollection as Collection;
use Kaw393939\Group\Models\Group as Group;
use Kaw393939\Group\Models\Role;

/**
 * Class GroupsController
 * @package Kaw393939\Group\Http\Controllers
 */
class GroupsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return (new Collection(Group::all()))->response()->setStatusCode(200);
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Group $group)
    {
        return (new Resource($group))->response()->setStatusCode(200);
    }

    /**
     * @param UpdateRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        $group->fill($request->all());
        return (new Resource($group))->response()->setStatusCode(200);
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GroupStoreRequest $request)
    {
        $user = Auth::user();
        $ownerRole = Role::where('name', 'owner')->first();
        $group = Group::create($request->all());
        $user->attachRole($ownerRole, $group);
        return (new Resource($group))->response()->setStatusCode(201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function mygroups()
    {
        $groups = Auth::user()->groups;
        return (new Collection($groups))->response()->setStatusCode(200);
    }

    /**
     * @param DeleteRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(GroupDestroyRequest $request, Group $group)
    {
        $group->delete();
        return response()->json(null, 204);
    }
}
