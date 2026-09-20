<?php

namespace App\Providers;

use App\Http\Requests\Auth\VerifyEmailRequest as AppVerifyEmailRequest;
use App\Translation\Translator;
use Carbon\CarbonImmutable;
use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\ExceptionResponse;
use Inertia\Inertia;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest as FortifyVerifyEmailRequest;
use URL;
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
            FortifyVerifyEmailRequest::class,
            AppVerifyEmailRequest::class
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

        $this->app->extend('translator', function ($service) {
            $translator = new Translator($service->getLoader(), $service->getLocale());
            $translator->setFallback($service->getFallback());

            return $translator;
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        Model::shouldBeStrict();
        Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
        Model::preventLazyLoading(! app()->isProduction());
        DB::prohibitDestructiveCommands(app()->isProduction());
        Vite::prefetch(concurrency: 3);
        JsonResource::withoutWrapping();

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );

        Gate::before(fn ($user, $ability) => $user->hasRole('super-admin') ? true : null);
    }
}
