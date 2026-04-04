<?php

namespace App\Http\Requests\Exams;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GlucoseRequest extends FormRequest
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
            'glucose_level' => ['required', 'numeric', 'min:0'],
            'glycated_hemoglobin' => ['required', 'numeric', 'min:0'],
            'estimated_average_glucose' => ['required', 'numeric', 'min:0'],
        ];
    }
}
