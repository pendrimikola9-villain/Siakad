<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Http\Resources\Api\LecturerResource;
use App\Http\Requests\Api\LecturerRequest;
use Illuminate\Http\JsonResponse;

class LecturerController extends Controller
{
    public function index()
    {
        return LecturerResource::collection(Lecturer::paginate(10));
    }

    public function store(LecturerRequest $request): LecturerResource
    {
        $lecturer = Lecturer::create($request->validated());
        return new LecturerResource($lecturer);
    }

    public function show(Lecturer $lecturer): LecturerResource
    {
        return new LecturerResource($lecturer);
    }

    public function update(LecturerRequest $request, Lecturer $lecturer): LecturerResource
    {
        $lecturer->update($request->validated());
        return new LecturerResource($lecturer);
    }

    public function destroy(Lecturer $lecturer): JsonResponse
    {
        $lecturer->delete();
        return response()->json(['message' => 'Dosen berhasil dihapus']);
    }
}
