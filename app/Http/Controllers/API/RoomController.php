<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Room::query();
//        dd($query);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->boolean('sort')) {
            $query->orderBy('id', 'desc');
        }

        $rooms = $query->paginate($perPage);

        return response()->json($rooms);
    }

    public function show(Room $room)
    {
        return response()->json($room);
    }

    public function store(StoreRoomRequest $request)
    {
        $validator = $request->validated();
        Room::query()->create($validator);
        return response()->json($request);
    }

    public function update(UpdateSubjectRequest $request, Room $room)
    {
        $validator = $request->validated();
        $room->update($validator);
        return response()->json([
            'message'=>"Room updated successfully"
        ],201);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Room destroy']);
    }
}