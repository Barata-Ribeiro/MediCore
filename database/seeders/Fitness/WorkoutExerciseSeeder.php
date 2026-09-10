<?php

namespace Database\Seeders\Fitness;

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\WorkoutExercise;
use App\Models\Fitness\WorkoutSection;
use Illuminate\Database\Seeder;

class WorkoutExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $section = WorkoutSection::factory()->create();
        $muscleGroup = MuscleGroup::factory()->for($section->workout->user)->create();
        $exercise = Exercise::factory()->for($section->workout->user)->hasAttached($muscleGroup)->create();

        WorkoutExercise::factory()->count(5)->for($section, 'section')->for($exercise)->for($muscleGroup)->sequence(
            ['order' => 0],
            ['order' => 1],
            ['order' => 2],
            ['order' => 3],
            ['order' => 4],
        )->create();
    }
}
