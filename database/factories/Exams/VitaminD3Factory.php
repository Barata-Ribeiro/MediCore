<?php

namespace Database\Factories\Exams;

use App\Models\Exams\VitaminD3;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VitaminD3>
 */
class VitaminD3Factory extends Factory
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
            'twenty_five_hydroxyvitamin_d3' => fake()->randomFloat(2, 5, 100),
            'report_date' => fake()->date(),
        ];
    }
}
