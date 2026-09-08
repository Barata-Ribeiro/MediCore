<?php

namespace Database\Seeders\Exams;

use App\Models\Exams\TgoAndTgp;
use App\Models\User;
use Illuminate\Database\Seeder;

class TgoAndTgpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $medicalFile = User::factory()->create()->medicalFile()->create();

        TgoAndTgp::factory()->count(5)->for($medicalFile)->create();
    }
}
