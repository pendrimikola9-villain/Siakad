<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Http\Resources\Api\ClassScheduleResource;
use App\Http\Requests\Api\ClassScheduleRequest;
use Illuminate\Http\JsonResponse;

class ClassScheduleController extends Controller
{
    public function index()
    {
        return ClassScheduleResource::collection(ClassSchedule::paginate(10));
    }

    public function store(ClassScheduleRequest $request): ClassScheduleResource
    {
        $schedule = ClassSchedule::create($request->validated());
        return new ClassScheduleResource($schedule);
    }

    public function show(ClassSchedule $classSchedule): ClassScheduleResource
    {
        return new ClassScheduleResource($classSchedule);
    }

    public function update(ClassScheduleRequest $request, ClassSchedule $classSchedule): ClassScheduleResource
    {
        $classSchedule->update($request->validated());
        return new ClassScheduleResource($classSchedule);
    }

    public function destroy(ClassSchedule $classSchedule): JsonResponse
    {
        $classSchedule->delete();
        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }
}
