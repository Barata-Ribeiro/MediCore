<?php

namespace Database\Factories\Exams;

use App\Models\Exams\LipidProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LipidProfile>
 */
class LipidProfileFactory extends Factory
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
            'total_cholesterol' => fake()->randomFloat(2, 100, 300),
            'hdl_cholesterol' => fake()->randomFloat(2, 20, 100),
            'ldl_cholesterol' => fake()->randomFloat(2, 40, 200),
            'vldl_cholesterol' => fake()->randomFloat(2, 5, 60),
            'triglycerides' => fake()->randomFloat(2, 40, 300),
            'report_date' => fake()->date(),
        ];
    }
}
