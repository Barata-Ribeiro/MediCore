<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use function in_array;

class SocialAuthRedirectController extends Controller
{
    private const array SUPPORTED_PROVIDERS = ['google'];

    public function __invoke(string $provider): InertiaResponse|SymfonyResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            return Inertia::render('auth/register')
                ->flash('toast', ['type' => 'error', 'message' => __('flash.social_auth.unsupported_provider_retry')]);
        }

        try {
            return Inertia::location(Socialite::driver($provider)->redirect()->getTargetUrl());
        } catch (Exception $e) {
            Log::warning('Failed to start social authentication redirect.', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return Inertia::render('auth/register')
                ->flash('toast', ['type' => 'error', 'message' => __('flash.social_auth.failed_redirect')]);
        }
    }
}
