<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Http\Resources\Api\MahasiswaResource;
use App\Http\Requests\Api\MahasiswaRequest;
use Illuminate\Http\JsonResponse;

class MahasiswaController extends Controller
{
    public function index()
    {
        return MahasiswaResource::collection(Mahasiswa::paginate(10));
    }

    public function store(MahasiswaRequest $request): MahasiswaResource
    {
        $mahasiswa = Mahasiswa::create($request->validated());
        return new MahasiswaResource($mahasiswa);
    }

    public function show(Mahasiswa $mahasiswa): MahasiswaResource
    {
        return new MahasiswaResource($mahasiswa);
    }

    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa): MahasiswaResource
    {
        $mahasiswa->update($request->validated());
        return new MahasiswaResource($mahasiswa);
    }

    public function destroy(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->delete();
        return response()->json(['message' => 'Mahasiswa berhasil dihapus']);
    }
}
