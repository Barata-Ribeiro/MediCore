<?php

namespace App\Providers;

use App\Interfaces\DashboardServiceInterface;
use App\Interfaces\Exams\CompleteBloodCountServiceInterface;
use App\Interfaces\Exams\GlucoseServiceInterface;
use App\Interfaces\Exams\LipidProfileServiceInterface;
use App\Interfaces\Exams\UreaAndCreatinineServiceInterface;
use App\Interfaces\Exams\VitaminB12ServiceInterface;
use App\Interfaces\Exams\VitaminD3ServiceInterface;
use App\Services\DashboardService;
use App\Services\Exams\CompleteBloodCountService;
use App\Services\Exams\GlucoseService;
use App\Services\Exams\LipidProfileService;
use App\Services\Exams\UreaAndCreatinineService;
use App\Services\Exams\VitaminB12Service;
use App\Services\Exams\VitaminD3Service;
use Carbon\CarbonImmutable;
use Gate;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\ExceptionResponse;
use Inertia\Inertia;
use Vite;

use function in_array;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

        $this->app->bind(
            LipidProfileServiceInterface::class,
            LipidProfileService::class
        );

        $this->app->bind(
            CompleteBloodCountServiceInterface::class,
            CompleteBloodCountService::class
        );

        $this->app->bind(
            GlucoseServiceInterface::class,
            GlucoseService::class
        );

        $this->app->bind(
            DashboardServiceInterface::class,
            DashboardService::class
        );

        $this->app->bind(
            VitaminD3ServiceInterface::class,
            VitaminD3Service::class
        );

        $this->app->bind(
            VitaminB12ServiceInterface::class,
            VitaminB12Service::class
        );

        $this->app->bind(
            UreaAndCreatinineServiceInterface::class,
            UreaAndCreatinineService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        Inertia::handleExceptionsUsing(function (ExceptionResponse $response) {
            if (in_array($response->statusCode(), [403, 404, 500, 503])) {
                return $response->render('error-page', [
                    'status' => $response->statusCode(),
                ])->withSharedData();
            }
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(app()->isProduction());

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );

        Vite::prefetch(concurrency: 3);

        Gate::before(fn ($user, $ability) => $user->hasRole('super-admin') ? true : null);
    }
}
