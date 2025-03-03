<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Subject::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->boolean('sort_by_date')) {
            $query->orderBy('id', 'desc');
        }

        $subjects = $query->paginate($perPage);

        return response()->json($subjects);
    }


    public function show(Subject $subject)
    {
        return response()->json($subject);
    }
    public function store(StoreSubjectRequest $request)
    {
        $validator = $request->validated();
        Subject::query()->create($validator);
        return response()->json([
            'message' => 'Subject created successfully'
        ], 201);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $validator = $request->validated();
        $subject->update($validator);
        return response()->json([
            'message' => 'Subject updated successfully'
        ], 201);

    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json([
            'message' => 'Subject deleted successfully'
        ], 201);
    }

}