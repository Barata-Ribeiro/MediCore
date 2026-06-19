<?php

namespace App\Http\Requests\Fitness;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateWorkoutRequest extends StoreWorkoutRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'sections.*.id' => ['sometimes', 'integer', 'exists:workout_sections,id'],
            'sections.*.exercises.*.id' => ['sometimes', 'integer', 'exists:workout_exercises,id'],
        ];
    }
}
