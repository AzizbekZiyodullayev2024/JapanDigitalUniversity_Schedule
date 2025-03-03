<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request)
    {
        $validated = $request->validated();
        $schedule = Schedule::query()
            ->where('subject_id', $validated['subject_id'])
            ->where('teacher_id', $validated['teacher_id']) // Changed from 'user_id'
            ->where('group_id', $validated['group_id'])
            ->where('pair', $validated['pair'])
            ->where('week_day', $validated['week_day'])
            ->where('date', $validated['date'])
            ->first();

        if ($schedule){
            return response()->json(['message'=>'Schedule already exists'], 409);
        }

        Schedule::query()->create($validated);
        return response()->json(['message'=>'Schedule created successfully'], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $validated = $request->validated(); // Validate input
        $schedule->update($validated); // Update the schedule

        return response()->json(['message' => 'Schedule updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::query()->find($id);
        $schedule->delete();
        return response()->json(['message'=>'Schedule deleted successfully'], 200);
    }
}