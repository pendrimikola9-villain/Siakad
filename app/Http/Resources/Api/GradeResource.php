<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mahasiswa_id' => (int) $this->mahasiswa_id,
            'course_id' => (int) $this->course_id,
            'nilai' => $this->nilai ? (float) $this->nilai : null,
            'grade' => $this->grade,
            'status_kunci' => $this->status_kunci,
            'mahasiswa' => new MahasiswaResource($this->whenLoaded('mahasiswa')),
            'course' => new CourseResource($this->whenLoaded('course')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
