<?php

namespace App\Http\Requests\Fitness;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filled_at' => ['nullable', 'date'],
            'next_change_at' => ['nullable', 'date', 'after_or_equal:filled_at'],
            'goal' => ['nullable', 'string', 'max:255'],
            'method' => ['nullable', 'string', 'max:255'],
            'rest_between_sets' => ['nullable', 'integer', 'min:0'],
            'rest_between_exercises' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],

            'sections' => ['sometimes', 'array'],
            'sections.*.name' => ['required', 'string', 'max:255'],
            'sections.*.order' => ['required', 'integer', 'min:0'],

            'sections.*.exercises' => ['sometimes', 'array'],
            'sections.*.exercises.*.exercise_id' => ['required', 'integer', 'exists:exercises,id'],
            'sections.*.exercises.*.muscle_group_id' => ['nullable', 'integer', 'exists:muscle_groups,id'],
            'sections.*.exercises.*.code' => ['nullable', 'string', 'max:10'],
            'sections.*.exercises.*.order' => ['required', 'integer', 'min:0'],
            'sections.*.exercises.*.sets' => ['required', 'integer', 'min:1'],
            'sections.*.exercises.*.reps' => ['required', 'string', 'max:50'],
            'sections.*.exercises.*.load' => ['nullable', 'numeric', 'min:0'],
            'sections.*.exercises.*.load_unit' => ['required', 'string', 'max:25'],
            'sections.*.exercises.*.rest_seconds' => ['nullable', 'integer', 'min:0'],
            'sections.*.exercises.*.notes' => ['nullable', 'string'],
        ];
    }
}
