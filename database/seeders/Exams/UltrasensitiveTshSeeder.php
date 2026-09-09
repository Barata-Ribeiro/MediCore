<?php

namespace Database\Seeders\Exams;

use App\Models\Exams\UltrasensitiveTsh;
use App\Models\User;
use Illuminate\Database\Seeder;

class UltrasensitiveTshSeeder extends Seeder
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

        UltrasensitiveTsh::factory()->count(5)->for($medicalFile)->create();
    }
}
