<?php

namespace App\Providers;

use App\Interfaces\Exams\CompleteBloodCountServiceInterface;
use App\Interfaces\Exams\GlucoseServiceInterface;
use App\Interfaces\Exams\LipidProfileServiceInterface;
use App\Services\Exams\CompleteBloodCountService;
use App\Services\Exams\GlucoseService;
use App\Services\Exams\LipidProfileService;
use Carbon\CarbonImmutable;
use Gate;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Vite;

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
			\App\Interfaces\DashboardServiceInterface::class,
			\App\Services\DashboardService::class
		);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
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
