<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('lecturer')?->id ?? $this->route('lecturer');

        return [
            'nidn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('lecturers')->ignore($id),
            ],
            'nik_karyawan' => 'nullable|string|max:30',
            'nama_dosen' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:20',
            'email_dosen' => [
                'required',
                'email',
                'max:255',
                Rule::unique('lecturers')->ignore($id),
            ],
            'alamat_lengkap' => 'required|string',
            'pendidikan_terakhir' => 'required|string|max:100',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'bidang_keahlian' => 'nullable|string|max:255',
        ];
    }
}
