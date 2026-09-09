<?php

namespace Database\Factories\Fitness;

use App\Models\Fitness\Workout;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workout>
 */
class WorkoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filled_at' => fake()->date(),
            'next_change_at' => null,
            'goal' => fake()->sentence(),
            'method' => fake()->words(3, true),
            'rest_between_sets' => 90,
            'rest_between_exercises' => 150,
            'is_active' => true,
        ];
    }
}
