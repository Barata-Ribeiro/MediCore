<?php

namespace App\Http\Requests\Fitness;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateWorkoutRequest extends StoreWorkoutRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $workoutId = (int) $this->route('workout')->id;

        return [
            ...parent::rules(),
            'sections.*.id' => [
                'sometimes',
                'integer',
                Rule::exists('workout_sections', 'id')->where('workout_id', $workoutId),
            ],
            'sections.*.exercises.*.id' => [
                'sometimes',
                'integer',
                Rule::exists('workout_exercises', 'id')->whereIn(
                    'workout_section_id',
                    $this->route('workout')->sections()->select('id'),
                ),
            ],
        ];
    }
}
