<?php

namespace App\Http\Requests\Settings;

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    use ProfileValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [...$this->profileRules($this->user()->id),
            'first_name' => ['sometimes', 'required_with:last_name', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required_with:first_name', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', 'string', 'in:male,female'],
            'gender_identity' => ['nullable', 'string', 'max:255'],
        ];
    }
}
