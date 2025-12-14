<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Requires authentication
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assessment_id' => 'nullable|uuid',
            'progress_data' => 'required|array',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'progress_data.required' => 'Data progress wajib diisi',
            'progress_data.array' => 'Data progress harus berupa array',
        ];
    }
}
