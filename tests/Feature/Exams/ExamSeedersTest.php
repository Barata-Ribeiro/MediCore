<?php

use App\Models\Exams\CompleteBloodCount;
use App\Models\Exams\Glucose;
use App\Models\Exams\LipidProfile;
use App\Models\Exams\TotalProteinsAndFractions;
use App\Models\Exams\UltrasensitiveTsh;
use App\Models\Exams\UreaAndCreatinine;
use App\Models\Exams\UricAcid;
use App\Models\Exams\VitaminB12;
use App\Models\Exams\VitaminD3;
use App\Models\User;
use Database\Seeders\Exams\CompleteBloodCountSeeder;
use Database\Seeders\Exams\GlucoseSeeder;
use Database\Seeders\Exams\LipidProfileSeeder;
use Database\Seeders\Exams\TotalProteinsAndFractionsSeeder;
use Database\Seeders\Exams\UltrasensitiveTshSeeder;
use Database\Seeders\Exams\UreaAndCreatinineSeeder;
use Database\Seeders\Exams\UricAcidSeeder;
use Database\Seeders\Exams\VitaminB12Seeder;
use Database\Seeders\Exams\VitaminD3Seeder;

dataset('exams seeders', [
    'CompleteBloodCount' => [CompleteBloodCount::class, CompleteBloodCountSeeder::class, 'complete_blood_counts', 'medicalFile.user'],
    'Glucose' => [Glucose::class, GlucoseSeeder::class, 'glucoses', 'medicalFile.user'],
    'LipidProfile' => [LipidProfile::class, LipidProfileSeeder::class, 'lipid_profiles', 'medicalFile.user'],
    'TotalProteinsAndFractions' => [TotalProteinsAndFractions::class, TotalProteinsAndFractionsSeeder::class, 'total_proteins_and_fractions', 'medicalFile.user'],
    'UltrasensitiveTsh' => [UltrasensitiveTsh::class, UltrasensitiveTshSeeder::class, 'ultrasensitive_tshs', 'medicalFile.user'],
    'UreaAndCreatinine' => [UreaAndCreatinine::class, UreaAndCreatinineSeeder::class, 'urea_and_creatinines', 'medicalFile.user'],
    'UricAcid' => [UricAcid::class, UricAcidSeeder::class, 'uric_acids', 'medicalFile.user'],
    'VitaminB12' => [VitaminB12::class, VitaminB12Seeder::class, 'vitamin_b12_s', 'medicalFile.user'],
    'VitaminD3' => [VitaminD3::class, VitaminD3Seeder::class, 'vitamin_d3_s', 'medicalFile.user'],
]);

it('seeds five samples for a separate owner without changing existing records', function (string $model, string $seeder, string $table, string $ownerPath) {
    $existing = $model::factory()->create();
    $original = $existing->getRawOriginal();
    $owner = data_get($existing, $ownerPath);

    $this->seed($seeder);

    $this->assertDatabaseCount($table, 6);
    $this->assertDatabaseHas($table, $original);
    $samples = $model::query()->with($ownerPath)->whereKeyNot($existing->id)->get();
    $ownerIds = $samples->map(fn ($sample): int => data_get($sample, $ownerPath)->id)->unique();
    expect($ownerIds)->toHaveCount(1)->not->toContain($owner->id);
})->with('exams seeders');

it('does not seed sample data outside local and testing environments', function (string $model, string $seeder, string $table, string $ownerPath, string $environment) {
    $userCount = User::query()->count();
    $this->app->instance('env', $environment);

    $this->app->make($seeder)->run();

    $this->assertDatabaseCount($table, 0);
    $this->assertDatabaseCount('users', $userCount);
})->with('exams seeders')->with(['production', 'staging']);
