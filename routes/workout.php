<?php

use App\Http\Controllers\Fitness\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('fitness')->group(function () {
    Route::resource('workouts', WorkoutController::class);
});
