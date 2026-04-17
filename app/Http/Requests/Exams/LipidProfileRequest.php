<?php

namespace App\Http\Requests\Exams;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\Attributes\FailOnUnknownFields;
use Illuminate\Foundation\Http\FormRequest;

#[FailOnUnknownFields]
class LipidProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report_date' => ['required', 'date'],
            'total_cholesterol' => ['required', 'numeric', 'min:0'],
            'hdl_cholesterol' => ['required', 'numeric', 'min:0'],
            'ldl_cholesterol' => ['required', 'numeric', 'min:0'],
            'vldl_cholesterol' => ['required', 'numeric', 'min:0'],
            'triglycerides' => ['required', 'numeric', 'min:0'],
        ];
    }
}
