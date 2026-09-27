<?php

namespace App\Http\Requests\Exams;

use App\Enums\ExamType;
use Illuminate\Foundation\Http\FormRequest;

class CompleteBloodCountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return ExamType::COMPLETE_BLOOD_COUNT->rules();
    }
}
