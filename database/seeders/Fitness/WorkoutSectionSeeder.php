<?php

namespace Database\Seeders\Fitness;

use App\Models\Fitness\Workout;
use App\Models\Fitness\WorkoutSection;
use Illuminate\Database\Seeder;

class WorkoutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $workout = Workout::factory()->create();

        WorkoutSection::factory()->count(5)->for($workout)->sequence(
            ['order' => 0],
            ['order' => 1],
            ['order' => 2],
            ['order' => 3],
            ['order' => 4],
        )->create();
    }
}
