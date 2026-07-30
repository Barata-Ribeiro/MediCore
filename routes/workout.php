<?php

use App\Http\Controllers\Fitness\ExerciseController;
use App\Http\Controllers\Fitness\MuscleGroupController;
use App\Http\Controllers\Fitness\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('fitness')->group(function () {
    Route::resource('workouts', WorkoutController::class);
    Route::resource('exercises', ExerciseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('muscle-groups', MuscleGroupController::class)->only(['index', 'store', 'update', 'destroy']);
});
