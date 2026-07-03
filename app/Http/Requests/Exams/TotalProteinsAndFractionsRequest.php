<?php

namespace App\Http\Requests\Exams;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TotalProteinsAndFractionsRequest extends FormRequest
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
            'total_proteins' => ['required', 'numeric', 'min:0'],
            'albumin' => ['required', 'numeric', 'min:0'],
            'globulin' => ['required', 'numeric', 'min:0'],
        ];
    }
}
