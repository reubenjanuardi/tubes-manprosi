<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
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
            'org_name' => 'required|string|max:255',
            'org_type' => 'required|string|max:255',
            'assessor_name' => 'required|string|max:255',
            'assessor_position' => 'required|string|max:255',
            'assessment_date' => 'required|date',
            'responses' => 'required|array',
            'responses.*.indicator_id' => 'required|integer|between:1,32',
            'responses.*.score' => 'required|integer|between:1,5',
            'responses.*.evidence_text' => 'nullable|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'org_name.required' => 'Nama organisasi wajib diisi',
            'org_type.required' => 'Tipe organisasi wajib diisi',
            'assessor_name.required' => 'Nama assessor wajib diisi',
            'assessor_position.required' => 'Jabatan assessor wajib diisi',
            'assessment_date.required' => 'Tanggal assessment wajib diisi',
            'responses.required' => 'Data responses wajib diisi',
            'responses.*.score.between' => 'Skor harus antara 1-5',
        ];
    }
}
