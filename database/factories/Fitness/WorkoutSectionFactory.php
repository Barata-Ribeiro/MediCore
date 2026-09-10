<?php

namespace Database\Factories\Fitness;

use App\Models\Fitness\Workout;
use App\Models\Fitness\WorkoutSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkoutSection>
 */
class WorkoutSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workout_id' => Workout::factory(),
            'name' => fake()->word(),
            'order' => 0,
        ];
    }
}
