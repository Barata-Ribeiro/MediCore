<?php

namespace Database\Seeders\Fitness;

use App\Models\Fitness\MuscleGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class MuscleGroupSeeder extends Seeder
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

        MuscleGroup::factory()->count(5)->for($user)->create();
    }
}
