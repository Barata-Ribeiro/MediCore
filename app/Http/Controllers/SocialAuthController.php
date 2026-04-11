<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use function in_array;

class SocialAuthController extends Controller
{
    private const array SUPPORTED_PROVIDERS = ['google'];

    public function redirect(string $provider): InertiaResponse|SymfonyResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            return Inertia::render('auth/register')
                ->flash('toast', ['type' => 'error', 'message' => 'Unsupported provider. Create an account using email and password instead.']);
        }

        try {
            return Inertia::location(Socialite::driver($provider)->redirect()->getTargetUrl());
        } catch (Exception $e) {
            Log::warning('Failed to start social authentication redirect.', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return Inertia::render('auth/register')
                ->flash('toast', ['type' => 'error', 'message' => 'Failed to redirect to provider. Please try again.']);
        }
    }

    public function callback(string $provider): InertiaResponse|RedirectResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            return Inertia::render('auth/login')
                ->flash('toast', ['type' => 'error', 'message' => 'Unsupported provider. Please try again.']);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = DB::transaction(function () use ($provider, $socialUser): User {
                $user = User::updateOrCreate(
                    ['provider_name' => $provider, 'provider_id' => $socialUser->getId()],
                    [
                        'name' => $socialUser->getName() ?: $socialUser->getNickname(),
                        'email' => $socialUser->getEmail(),
                        'avatar' => $socialUser->getAvatar(),
                        'email_verified_at' => now(),
                        'provider_token' => $socialUser->token,
                        'provider_refresh_token' => $socialUser->refreshToken ?? null,
                        'registration_domain' => request()->getHost(),
                    ]
                );

                $user->assignRole('user');
                $user->medicalFile()->firstOrCreate([]);

                return $user;
            });

            Auth::login($user, true);

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Successfully authenticated with '.ucfirst($provider).'.']);

            return to_route('dashboard');
        } catch (Exception $e) {
            Log::warning('Failed to authenticate with social provider.', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return Inertia::render('auth/login')
                ->flash('toast', ['type' => 'error', 'message' => 'Failed to authenticate with provider. Please try again.']);
        }
    }
}
