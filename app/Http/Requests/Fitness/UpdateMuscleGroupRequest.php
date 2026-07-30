<?php

namespace App\Http\Requests\Fitness;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMuscleGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        $muscleGroupId = (int) $this->route('muscle_group')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('muscle_groups', 'name')->where('user_id', $this->user()?->id)->ignore($muscleGroupId),
            ],
        ];
    }
}
