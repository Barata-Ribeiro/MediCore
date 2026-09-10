<?php

namespace Database\Factories\Exams;

use App\Models\Exams\CompleteBloodCount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompleteBloodCount>
 */
class CompleteBloodCountFactory extends Factory
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
            'hematocrit' => fake()->randomFloat(2, 30, 55),
            'hemoglobin' => fake()->randomFloat(2, 10, 18),
            'red_blood_cell_count' => fake()->randomFloat(2, 3, 6),
            'mean_corpuscular_volume' => fake()->randomFloat(2, 70, 110),
            'mean_corpuscular_hemoglobin' => fake()->randomFloat(2, 20, 40),
            'mean_corpuscular_hemoglobin_concentration' => fake()->randomFloat(2, 25, 40),
            'red_blood_cell_distribution_width' => fake()->randomFloat(2, 10, 20),
            'leukocyte_count' => fake()->randomFloat(2, 4, 12),
            'rod_neutrophil_count' => fake()->randomFloat(2, 0, 1),
            'segmented_neutrophil_count' => fake()->randomFloat(2, 1, 8),
            'lymphocyte_count' => fake()->randomFloat(2, 1, 4),
            'monocyte_count' => fake()->randomFloat(2, 0, 1),
            'eosinophil_count' => fake()->randomFloat(2, 0, 1),
            'basophil_count' => fake()->randomFloat(2, 0, 0.2),
            'metamyelocyte_count' => fake()->randomFloat(2, 0, 1),
            'promyelocyte_count' => fake()->randomFloat(2, 0, 1),
            'atypical_cell_count' => fake()->randomFloat(2, 0, 1),
            'platelet_count' => fake()->randomFloat(2, 100, 450),
            'report_date' => fake()->date(),
        ];
    }
}
