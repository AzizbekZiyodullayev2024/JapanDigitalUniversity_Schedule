<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $validator = $request->validated();
        $user = User::query()->find($validator['user_id']);

        $user->roles()->attach($validator['role_id'], ['created_at' => now(), 'updated_at' => now()]);

        return response()->json([
            'message' => 'Role attached to user'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $validator = $request->validated();
        $user = User::query()->find($id);

        $user->roles()->detach($validator['role_id']);

        return response()->json([
            'message' => 'Role detached from user'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $validator = $request->validate([
            'role_id'=>'required|exists:roles,id',
        ]);
        $user = User::query()->find($id);

        $user->roles()->detach($validator['role_id']);

        return response()->json([
            'message' => 'Role detached from user'
        ]);
    }
}