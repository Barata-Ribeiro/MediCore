<?php

namespace App\Http\Requests\Fitness;

use App\Models\Fitness\Exercise;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

use function is_array;

class StoreWorkoutRequest extends FormRequest
{
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
            'sections.*.exercises.*.exercise_id' => [
                'required',
                'integer',
                Rule::exists('exercises', 'id')->where('user_id', $this->user()?->id),
            ],
            'sections.*.exercises.*.muscle_group_id' => [
                'nullable',
                'integer',
                Rule::exists('muscle_groups', 'id')->where('user_id', $this->user()?->id),
            ],
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

    /**
     * Configure the validator instance.
     *
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [function (Validator $validator): void {
            $sections = $this->input('sections', []);

            if (! is_array($sections)) {
                return;
            }

            foreach ($sections as $sectionIndex => $sectionPayload) {
                if (! is_array($sectionPayload)) {
                    continue;
                }

                $exercises = $sectionPayload['exercises'] ?? [];

                if (! is_array($exercises)) {
                    continue;
                }

                foreach ($exercises as $exerciseIndex => $exercisePayload) {
                    if (! is_array($exercisePayload)) {
                        continue;
                    }

                    $exerciseId = $exercisePayload['exercise_id'] ?? null;
                    $muscleGroupId = $exercisePayload['muscle_group_id'] ?? null;

                    if (! is_numeric($exerciseId) || ! is_numeric($muscleGroupId)) {
                        continue;
                    }

                    $isLinked = Exercise::query()
                        ->whereBelongsTo($this->user())
                        ->whereKey((int) $exerciseId)
                        ->whereHas('muscleGroups', fn ($query) => $query->whereKey((int) $muscleGroupId))
                        ->exists();

                    if ($isLinked) {
                        continue;
                    }

                    $validator->errors()->add(
                        "sections.$sectionIndex.exercises.$exerciseIndex.muscle_group_id",
                        __('validation.workout_muscle_group_mismatch'),
                    );
                }
            }
        }];
    }
}
