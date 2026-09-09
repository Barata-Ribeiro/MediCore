<?php

namespace Database\Factories\Exams;

use App\Models\Exams\TotalProteinsAndFractions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TotalProteinsAndFractions>
 */
class TotalProteinsAndFractionsFactory extends Factory
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
            'total_proteins' => fake()->randomFloat(2, 5, 9),
            'albumin' => fake()->randomFloat(2, 2, 5),
            'globulin' => fake()->randomFloat(2, 1, 4),
            'report_date' => fake()->date(),
        ];
    }
}
