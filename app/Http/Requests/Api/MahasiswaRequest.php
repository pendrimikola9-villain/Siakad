<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('mahasiswa')?->id ?? $this->route('mahasiswa');

        return [
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswas')->ignore($id),
            ],
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('mahasiswas')->ignore($id),
            ],
            'no_hp' => 'required|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'prodi' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
            'dosen_pembimbing' => 'nullable|string|max:255',
            'ipk_terakhir' => 'nullable|numeric|min:0|max:4',
            'status_mahasiswa' => 'required|string|max:50',
        ];
    }
}
