<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mahasiswa_id' => (int) $this->mahasiswa_id,
            'lecturer_id' => (int) $this->lecturer_id,
            'room_id' => (int) $this->room_id,
            'tanggal_bimbingan' => $this->tanggal_bimbingan,
            'topik_bimbingan' => $this->topik_bimbingan,
            'status_bimbingan' => $this->status_bimbingan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
