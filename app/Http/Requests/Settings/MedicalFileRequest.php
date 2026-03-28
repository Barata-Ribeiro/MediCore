<?php

namespace App\Http\Requests\Settings;

use App\Enums\BloodType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicalFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blood_type' => ['nullable', 'string', Rule::in(array_map(fn (BloodType $type) => $type->value, BloodType::cases()))],
            'allergies' => ['nullable', 'string', 'max:255'],
            'diseases' => ['nullable', 'string', 'max:255'],
            'medications' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'emergency_contact_name' => ['nullable', 'required_with_all:emergency_contact_phone_number,emergency_contact_relationship', 'string', 'max:255'],
            'emergency_contact_phone_number' => ['nullable', 'required_with_all:emergency_contact_name,emergency_contact_relationship', 'string', 'max:20'],
            'emergency_contact_relationship' => ['nullable', 'required_with_all:emergency_contact_name,emergency_contact_phone_number', 'string', 'max:255'],
        ];
    }
}
