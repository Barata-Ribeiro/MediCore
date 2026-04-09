<?php

namespace App\Http\Requests\Exams;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VitaminB12Request extends FormRequest
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
            'vitamin_b12_level' => ['required', 'numeric', 'min:0'],
        ];
    }
}
