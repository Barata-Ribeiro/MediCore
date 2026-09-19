<?php

use App\Interfaces\DashboardServiceInterface;
use App\Interfaces\Exams\CompleteBloodCountServiceInterface;
use App\Interfaces\Exams\GlucoseServiceInterface;
use App\Interfaces\Exams\LipidProfileServiceInterface;
use App\Interfaces\Exams\TgoAndTgpServiceInterface;
use App\Interfaces\Exams\TotalProteinsAndFractionsServiceInterface;
use App\Interfaces\Exams\UltrasensitiveTshServiceInterface;
use App\Interfaces\Exams\UreaAndCreatinineServiceInterface;
use App\Interfaces\Exams\UricAcidServiceInterface;
use App\Interfaces\Exams\VitaminB12ServiceInterface;
use App\Interfaces\Exams\VitaminD3ServiceInterface;
use App\Interfaces\Fitness\ExerciseServiceInterface;
use App\Interfaces\Fitness\MuscleGroupServiceInterface;
use App\Services\DashboardService;
use App\Services\Exams\CompleteBloodCountService;
use App\Services\Exams\GlucoseService;
use App\Services\Exams\LipidProfileService;
use App\Services\Exams\TgoAndTgpService;
use App\Services\Exams\TotalProteinsAndFractionsService;
use App\Services\Exams\UltrasensitiveTshService;
use App\Services\Exams\UreaAndCreatinineService;
use App\Services\Exams\UricAcidService;
use App\Services\Exams\VitaminB12Service;
use App\Services\Exams\VitaminD3Service;
use App\Services\Fitness\ExerciseService;
use App\Services\Fitness\MuscleGroupService;

test('resolves service interfaces without provider bindings', function (string $interface, string $service) {
    expect(app()->bound($interface))->toBeFalse();

    $instance = app($interface);

    expect($instance)->toBeInstanceOf($interface)->toBeInstanceOf($service);
    expect(app($interface))->not->toBe($instance);
})->with([
    'DashboardService' => [DashboardServiceInterface::class, DashboardService::class],
    'Exams/CompleteBloodCountService' => [CompleteBloodCountServiceInterface::class, CompleteBloodCountService::class],
    'Exams/GlucoseService' => [GlucoseServiceInterface::class, GlucoseService::class],
    'Exams/LipidProfileService' => [LipidProfileServiceInterface::class, LipidProfileService::class],
    'Exams/TgoAndTgpService' => [TgoAndTgpServiceInterface::class, TgoAndTgpService::class],
    'Exams/TotalProteinsAndFractionsService' => [TotalProteinsAndFractionsServiceInterface::class, TotalProteinsAndFractionsService::class],
    'Exams/UltrasensitiveTshService' => [UltrasensitiveTshServiceInterface::class, UltrasensitiveTshService::class],
    'Exams/UreaAndCreatinineService' => [UreaAndCreatinineServiceInterface::class, UreaAndCreatinineService::class],
    'Exams/UricAcidService' => [UricAcidServiceInterface::class, UricAcidService::class],
    'Exams/VitaminB12Service' => [VitaminB12ServiceInterface::class, VitaminB12Service::class],
    'Exams/VitaminD3Service' => [VitaminD3ServiceInterface::class, VitaminD3Service::class],
    'Fitness/ExerciseService' => [ExerciseServiceInterface::class, ExerciseService::class],
    'Fitness/MuscleGroupService' => [MuscleGroupServiceInterface::class, MuscleGroupService::class],
]);
