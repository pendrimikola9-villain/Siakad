<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ConsultationLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id' => 'required|integer|exists:mahasiswas,id',
            'lecturer_id' => 'required|integer|exists:lecturers,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'tanggal_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required|string|max:255',
            'status_bimbingan' => 'required|string|max:50',
        ];
    }
}
