<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('course')?->id ?? $this->route('course');

        return [
            'kode_mk' => [
                'required',
                'string',
                'max:20',
                Rule::unique('courses')->ignore($id),
            ],
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'status_validasi' => 'nullable|string|max:50',
            'catatan_tolak' => 'nullable|string',
        ];
    }
}
