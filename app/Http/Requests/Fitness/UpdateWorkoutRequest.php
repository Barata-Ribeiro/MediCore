<?php

namespace App\Http\Requests\Fitness;

use App\Models\Fitness\Workout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use LogicException;

class UpdateWorkoutRequest extends StoreWorkoutRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $workout = $this->route('workout');

        if (! $workout instanceof Workout) {
            throw new LogicException('The workout route parameter must be model-bound.');
        }

        return [
            ...parent::rules(),
            'sections.*.id' => [
                'sometimes',
                'integer',
                Rule::exists('workout_sections', 'id')->where('workout_id', $workout->id),
            ],
            'sections.*.exercises.*.id' => [
                'sometimes',
                'integer',
                Rule::exists('workout_exercises', 'id')->where(
                    fn (Builder $query) => $query->whereIn(
                        'workout_section_id',
                        $workout->sections()->select('id'),
                    ),
                ),
            ],
        ];
    }
}
