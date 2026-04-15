<?php

use App\Http\Controllers\Exams\CompleteBloodCountController;
use App\Http\Controllers\Exams\GlucoseController;
use App\Http\Controllers\Exams\LipidProfileController;
use App\Http\Controllers\Exams\UltrasensitiveTshController;
use App\Http\Controllers\Exams\UreaAndCreatinineController;
use App\Http\Controllers\Exams\VitaminB12Controller;
use App\Http\Controllers\Exams\VitaminD3Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('exams')->group(function () {
    Route::resource('complete-blood-count', CompleteBloodCountController::class);
    Route::resource('glucose', GlucoseController::class);
    Route::resource('lipid-profile', LipidProfileController::class);
    Route::resource('ultrasensitive-tsh', UltrasensitiveTshController::class);
    Route::resource('urea-and-creatinine', UreaAndCreatinineController::class);
    Route::resource('vitamin-b12', VitaminB12Controller::class);
    Route::resource('vitamin-d3', VitaminD3Controller::class);
});
