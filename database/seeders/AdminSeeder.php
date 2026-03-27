<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $admin = User::firstOrCreate(
                ['email' => config('app.admin_email')],
                [
                    'name' => config('app.admin_name'),
                    'email' => config('app.admin_email'),
                    'password' => config('app.admin_password'),
                    'email_verified_at' => now(),
                ]
            )
                ->assignRole('super-admin')
                ->medicalFile()->create()
                ->profile()->create([
                    'first_name' => config('app.admin_first_name'),
                    'last_name' => config('app.admin_last_name'),
                ]);

            Log::info('Super Admin user seeded successfully!', ['email' => $admin->email]);
        } catch (Exception $e) {
            Log::error('Error seeding users!', ['error' => $e->getMessage()]);
        }
    }
}
