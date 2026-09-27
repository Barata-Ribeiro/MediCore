<?php

namespace App\Http\Requests\Exams;

use App\Enums\ExamType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TgoAndTgpRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tgo_level' => __('validation.attributes.tgo_level'),
            'tgp_level' => __('validation.attributes.tgp_level'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ExamType::TGO_AND_TGP->rules();
    }
}
