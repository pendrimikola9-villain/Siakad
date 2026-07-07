<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Http\Resources\Api\RoomResource;
use App\Http\Requests\Api\RoomRequest;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function index()
    {
        return RoomResource::collection(Room::paginate(10));
    }

    public function store(RoomRequest $request): RoomResource
    {
        $room = Room::create($request->validated());
        return new RoomResource($room);
    }

    public function show(Room $room): RoomResource
    {
        return new RoomResource($room);
    }

    public function update(RoomRequest $request, Room $room): RoomResource
    {
        $room->update($request->validated());
        return new RoomResource($room);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();
        return response()->json(['message' => 'Ruangan berhasil dihapus']);
    }
}
