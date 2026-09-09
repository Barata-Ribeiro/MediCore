<?php

namespace Database\Factories\Fitness;

use App\Models\Fitness\Exercise;
use App\Models\Fitness\WorkoutExercise;
use App\Models\Fitness\WorkoutSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkoutExercise>
 */
class WorkoutExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workout_section_id' => WorkoutSection::factory(),
            'exercise_id' => fn (array $attributes): int => Exercise::factory()
                ->for(WorkoutSection::query()->whereKey($attributes['workout_section_id'])->firstOrFail()->workout->user)
                ->create()->id,
            'muscle_group_id' => null,
            'code' => 'A1',
            'order' => 0,
            'sets' => 3,
            'reps' => '8-12',
            'load' => 20,
            'load_unit' => 'kg',
            'rest_seconds' => 90,
            'notes' => null,
        ];
    }
}
