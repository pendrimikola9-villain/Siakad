<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'nama_ruangan' => $this->nama_ruangan,
            'kapasitas' => (int) $this->kapasitas,
            'jenis_ruangan' => $this->jenis_ruangan,
            'lokasi_gedung' => $this->lokasi_gedung,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
