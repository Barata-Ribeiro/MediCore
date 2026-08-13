<?php

use App\Http\Controllers\Fitness\ExerciseController;
use App\Http\Controllers\Fitness\MuscleGroupController;
use App\Http\Controllers\Fitness\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('fitness')->group(function () {
    Route::resource('workouts', WorkoutController::class);
    Route::resource('exercises', ExerciseController::class);
    Route::resource('muscle-groups', MuscleGroupController::class);
});
