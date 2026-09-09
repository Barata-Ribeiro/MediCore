<?php

namespace Database\Seeders\Fitness;

use App\Models\Fitness\Exercise;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
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

        Exercise::factory()->count(5)->for($user)->create();
    }
}
