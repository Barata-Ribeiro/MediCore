<?php

use App\Http\Controllers\SocialAuthController;
use App\Interfaces\DashboardServiceInterface;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function (DashboardServiceInterface $dashboardService) {
        syncLangFiles('dashboard');

        return Inertia::render('dashboard', [
            'data' => $dashboardService->getDashboardData(),
        ]);
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/exams.php';
