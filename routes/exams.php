<?php

use App\Http\Controllers\Exams\CompleteBloodCountController;
use App\Http\Controllers\Exams\GlucoseController;
use App\Http\Controllers\Exams\LipidProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('exams')->group(function () {
    Route::resource('lipid-profile', LipidProfileController::class);
    Route::resource('complete-blood-count', CompleteBloodCountController::class);
    Route::resource('glucose', GlucoseController::class);
});
