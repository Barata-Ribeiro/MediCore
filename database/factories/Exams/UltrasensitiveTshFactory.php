<?php

namespace Database\Factories\Exams;

use App\Models\Exams\UltrasensitiveTsh;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UltrasensitiveTsh>
 */
class UltrasensitiveTshFactory extends Factory
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
            'tsh_level' => fake()->randomFloat(2, 0, 10),
            'report_date' => fake()->date(),
        ];
    }
}
