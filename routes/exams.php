<?php

use App\Http\Controllers\Exams\LipidProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('exams')->group(function () {
    Route::resource('lipid-profile', LipidProfileController::class);
});
