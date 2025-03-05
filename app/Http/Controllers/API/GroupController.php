<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $groups = Group::query()->paginate($perPage);
        return response()->json($groups);
    }

    public function show(Group $group)
    {
        return response()->json($group);
    }

    public function store(StoreGroupRequest $request)
    {
        $validator = $request->validated();
        Group::query()->create($validator);
        return response()->json([
            'message' => 'Group created successfully'
        ], 201);
    }

    public function update(UpdateGroupRequest $request, $group)
    {
        $validator = $request->validated();
        $group->update($validator);
        return response()->json([
            'message' => 'Group updated successfully'
        ], 201);

    }

    public function destroy( Group $group)
    {
        $group->delete();
        return response()->json(['message' => 'Group destroy']);
    }
}