<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Resources\Api\CourseResource;
use App\Http\Requests\Api\CourseRequest;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index()
    {
        return CourseResource::collection(Course::paginate(10));
    }

    public function store(CourseRequest $request): CourseResource
    {
        $course = Course::create($request->validated());
        return new CourseResource($course);
    }

    public function show(Course $course): CourseResource
    {
        return new CourseResource($course);
    }

    public function update(CourseRequest $request, Course $course): CourseResource
    {
        $course->update($request->validated());
        return new CourseResource($course);
    }

    public function destroy(Course $course): JsonResponse
    {
        $course->delete();
        return response()->json(['message' => 'Course berhasil dihapus']);
    }
}
