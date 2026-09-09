<?php

namespace Database\Factories\Exams;

use App\Models\Exams\UreaAndCreatinine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UreaAndCreatinine>
 */
class UreaAndCreatinineFactory extends Factory
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
            'urea_level' => fake()->randomFloat(2, 10, 70),
            'creatinine_level' => fake()->randomFloat(2, 0.5, 2),
            'report_date' => fake()->date(),
        ];
    }
}
