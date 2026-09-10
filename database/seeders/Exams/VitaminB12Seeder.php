<?php

namespace Database\Seeders\Exams;

use App\Models\Exams\VitaminB12;
use App\Models\User;
use Illuminate\Database\Seeder;

class VitaminB12Seeder extends Seeder
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

        VitaminB12::factory()->count(5)->for($medicalFile)->create();
    }
}
