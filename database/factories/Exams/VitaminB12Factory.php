<?php

namespace Database\Factories\Exams;

use App\Models\Exams\VitaminB12;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VitaminB12>
 */
class VitaminB12Factory extends Factory
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
            'vitamin_b12_level' => fake()->randomFloat(2, 100, 1000),
            'report_date' => fake()->date(),
        ];
    }
}
