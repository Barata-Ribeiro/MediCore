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

    public function callback(string $provider): InertiaResponse|RedirectResponse
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            return Inertia::render('auth/login')
                ->flash('toast', ['type' => 'error', 'message' => __('flash.social_auth.unsupported_provider_retry')]);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = DB::transaction(function () use ($provider, $socialUser): User {
                $user = User::query()
                    ->where(fn ($q) => $q->where('provider_name', $provider)->where('provider_id', $socialUser->getId()))
                    ->orWhere('email', $socialUser->getEmail())
                    ->first();

                $attributes = [
                    'name' => $socialUser->getName() ?: $socialUser->getNickname(),
                    'avatar' => $socialUser->getAvatar(),
                    'email' => $socialUser->getEmail(),
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                    'provider_refresh_token' => $socialUser->refreshToken ?? null,
                    'registration_domain' => request()->getHost(),
                ];

                if ($user) {
                    $user->update($attributes);
                    $user->markEmailAsVerified();
                } else {
                    $user = User::query()->create($attributes);
                    $user->assignRole('user');
                    $user->markEmailAsVerified();
                    $user->medicalFile()->firstOrCreate([]);
                }

                return $user;
            });

            Auth::login($user, true);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.social_auth.successful_auth', ['provider' => ucfirst($provider)])]);

            return to_route('dashboard');
        } catch (Exception $e) {
            Log::warning('Failed to authenticate with social provider.', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return Inertia::render('auth/login')
                ->flash('toast', ['type' => 'error', 'message' => __('flash.social_auth.failed_auth', ['provider' => ucfirst($provider)])]);
        }
    }
}
