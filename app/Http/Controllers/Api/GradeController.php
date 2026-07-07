<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Http\Resources\Api\GradeResource;
use App\Http\Requests\Api\GradeRequest;
use Illuminate\Http\JsonResponse;

class GradeController extends Controller
{
    public function index()
    {
        return GradeResource::collection(Grade::with(['mahasiswa', 'course'])->paginate(10));
    }

    public function store(GradeRequest $request): GradeResource
    {
        $grade = Grade::create($request->validated());
        return new GradeResource($grade->load(['mahasiswa', 'course']));
    }

    public function show(Grade $grade): GradeResource
    {
        return new GradeResource($grade->load(['mahasiswa', 'course']));
    }

    public function update(GradeRequest $request, Grade $grade): GradeResource
    {
        $grade->update($request->validated());
        return new GradeResource($grade->load(['mahasiswa', 'course']));
    }

    public function destroy(Grade $grade): JsonResponse
    {
        $grade->delete();
        return response()->json(['message' => 'Grade berhasil dihapus']);
    }
}
