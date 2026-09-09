<?php

namespace Database\Factories\Exams;

use App\Models\Exams\Glucose;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Glucose>
 */
class GlucoseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'medical_file_id' => fn (): int => User::factory()->create()->medicalFile()->create()->id,
            'glucose_level' => fake()->randomFloat(2, 60, 200),
            'glycated_hemoglobin' => fake()->randomFloat(2, 4, 10),
            'estimated_average_glucose' => fake()->randomFloat(2, 60, 240),
            'report_date' => fake()->date(),
        ];
    }
}
