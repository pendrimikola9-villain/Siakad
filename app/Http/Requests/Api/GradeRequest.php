<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id' => 'required|integer|exists:mahasiswas,id',
            'course_id' => 'required|integer|exists:courses,id',
            'nilai' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:5',
            'status_kunci' => 'required|string|in:Draft,Locked',
        ];
    }
}
