<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
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
            'nim' => $this->nim,
            'nama' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'nama_ayah' => $this->nama_ayah,
            'nama_ibu' => $this->nama_ibu,
            'prodi' => $this->prodi,
            'semester' => (int) $this->semester,
            'dosen_pembimbing' => $this->dosen_pembimbing,
            'ipk_terakhir' => (float) $this->ipk_terakhir,
            'status_mahasiswa' => $this->status_mahasiswa,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
