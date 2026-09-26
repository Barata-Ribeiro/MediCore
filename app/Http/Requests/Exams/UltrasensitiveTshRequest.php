<?php

namespace App\Http\Requests\Exams;

use App\Enums\ExamType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UltrasensitiveTshRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ExamType::ULTRASENSITIVE_TSH->rules();
    }
}
