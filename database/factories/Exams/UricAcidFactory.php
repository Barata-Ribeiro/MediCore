<?php

namespace Database\Factories\Exams;

use App\Models\Exams\UricAcid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UricAcid>
 */
class UricAcidFactory extends Factory
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
            'uric_acid_level' => fake()->randomFloat(2, 2, 10),
            'report_date' => fake()->date(),
        ];
    }
}
