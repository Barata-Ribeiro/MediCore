<?php

namespace Database\Factories\Exams;

use App\Models\Exams\TgoAndTgp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TgoAndTgp>
 */
class TgoAndTgpFactory extends Factory
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
            'tgo_level' => fake()->randomFloat(2, 0, 200),
            'tgp_level' => fake()->randomFloat(2, 0, 200),
            'report_date' => fake()->date(),
        ];
    }
}
