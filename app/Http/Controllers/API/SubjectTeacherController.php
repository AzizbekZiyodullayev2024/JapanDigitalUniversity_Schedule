<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectTeacherController extends Controller
{

    public function store(Request $request)
    {
        $validator = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $teacher = User::query()->find($validator['user_id']);
        $teacher->subjects()->attach($validator['subject_id']);

        return response()->json(['message' => 'Subject Teacher Added'], 201);
    }
    public function update(string $id, Request $request)
    {
        $validatedData = $request->validate([
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $teacher = User::findOrFail($id);

        // Agar `subjects()` Many-to-Many bog‘lanish bo‘lsa
        $teacher->subjects()->detach($validatedData['subject_id']);

        return response()->json([
            'message' => 'Teacher detached from subject'
        ]);
    }

    public function destroy(string $id, Request $request)
    {
        $validator=$request->validate([
            'subject_id'=>'required|exists:subjects,id'
        ]);
        $teacher=User::query()->findOrFail($validator['subject_id']);
        $teacher->subjects()->detach($id);
        return response()->json(['message'=>'Teacher detached from group successfully'], 200);
    }


}