<?php

namespace Database\Seeders\Exams;

use App\Models\Exams\UreaAndCreatinine;
use App\Models\User;
use Illuminate\Database\Seeder;

class UreaAndCreatinineSeeder extends Seeder
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

        UreaAndCreatinine::factory()->count(5)->for($medicalFile)->create();
    }
}
