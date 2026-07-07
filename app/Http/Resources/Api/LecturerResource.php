<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LecturerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nidn' => $this->nidn,
            'nik_karyawan' => $this->nik_karyawan,
            'nama_dosen' => $this->nama_dosen,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'no_hp' => $this->no_hp,
            'email_dosen' => $this->email_dosen,
            'alamat_lengkap' => $this->alamat_lengkap,
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'jabatan_fungsional' => $this->jabatan_fungsional,
            'bidang_keahlian' => $this->bidang_keahlian,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
