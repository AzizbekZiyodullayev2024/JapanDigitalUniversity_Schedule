<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;


class GroupSubjectController extends Controller
{

    public function store(Request $request)
    {
        $validator = $request->validate([
            'group_id'=>'required|exists:groups,id',
            'subject_id'=>'required|exists:subjects,id'
        ]);

        $group=Group::query()->findOrFail($validator['group_id']);
        $group->subjects()->attach($validator['subject_id'], ['created_at' => now(), 'updated_at' => now()]);


        return response()->json(['message' => 'Subject attached to group successfully'], 201);
    }
    public function update(string $id, Request $request)
    {
        $validator = $request->validate([
            'subject_id'=>'required|exists:subjects,id'
        ]);
        $group=Group::query()->findOrFail($id);

        $group->subjects()->detach($validator['subject_id']);

        return response()->json(['message'=>'Subject update from group successfully'], 200);

    }
    public function destroy(string $id,Request $request)
    {
        $validator=$request->validate([
           'group_id'=>'required|exists:groups,id'
        ]);
        $group=Group::query()->findOrFail($validator['group_id']);
        $group->subjects()->detach($id);
        return response()->json(['message'=>'Subject detached from group successfully'], 200);
    }

}