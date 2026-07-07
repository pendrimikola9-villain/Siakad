<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConsultationLog;
use App\Http\Resources\Api\ConsultationLogResource;
use App\Http\Requests\Api\ConsultationLogRequest;
use Illuminate\Http\JsonResponse;

class ConsultationLogController extends Controller
{
    public function index()
    {
        return ConsultationLogResource::collection(ConsultationLog::paginate(10));
    }

    public function store(ConsultationLogRequest $request): ConsultationLogResource
    {
        $log = ConsultationLog::create($request->validated());
        return new ConsultationLogResource($log);
    }

    public function show(ConsultationLog $consultationLog): ConsultationLogResource
    {
        return new ConsultationLogResource($consultationLog);
    }

    public function update(ConsultationLogRequest $request, ConsultationLog $consultationLog): ConsultationLogResource
    {
        $consultationLog->update($request->validated());
        return new ConsultationLogResource($consultationLog);
    }

    public function destroy(ConsultationLog $consultationLog): JsonResponse
    {
        $consultationLog->delete();
        return response()->json(['message' => 'Log bimbingan berhasil dihapus']);
    }
}
