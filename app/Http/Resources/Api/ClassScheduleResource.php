<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_id' => (int) $this->course_id,
            'lecturer_id' => (int) $this->lecturer_id,
            'room_id' => (int) $this->room_id,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'kelas' => $this->kelas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
