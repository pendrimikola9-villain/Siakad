<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_mk' => $this->kode_mk,
            'nama_mk' => $this->nama_mk,
            'sks' => (int) $this->sks,
            'semester' => (int) $this->semester,
            'status_validasi' => $this->status_validasi,
            'catatan_tolak' => $this->catatan_tolak,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
