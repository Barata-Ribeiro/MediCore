<?php

namespace Database\Seeders\Fitness;

use App\Models\Fitness\Workout;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $user = User::factory()->create();

        Workout::factory()->count(5)->for($user)->create();
    }
}
