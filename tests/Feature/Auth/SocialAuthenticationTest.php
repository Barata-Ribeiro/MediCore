<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

test('social auth redirect returns an inertia location for inertia requests', function () {
    Socialite::fake('google');

    $response = $this->withHeaders([
        'X-Inertia' => 'true',
        'X-Inertia-Version' => app(HandleInertiaRequests::class)->version(request()),
    ])->get(route('social.redirect', ['provider' => 'google'], absolute: false));

    $response->assertStatus(409);
    $response->assertHeader('X-Inertia-Location', 'https://socialite.fake/google/authorize');
});

test('social auth redirect redirects normally for standard requests', function () {
    Socialite::fake('google');

    $response = $this->get(route('social.redirect', ['provider' => 'google'], absolute: false));

    $response->assertRedirect('https://socialite.fake/google/authorize');
});

test('users can authenticate using google callback', function () {
    Socialite::fake(
        'google',
        (new SocialiteUser)->map([
            'id' => 'google-123',
            'name' => 'Google User',
            'email' => 'google@example.com',
            'avatar' => 'https://example.com/avatar.png',
        ])->setToken('fake-token')->setRefreshToken('fake-refresh-token')
    );

    $response = $this->get(route('social.callback', ['provider' => 'google'], absolute: false));

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticated();

    $this->get(route('social.callback', ['provider' => 'google'], absolute: false))
        ->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->firstWhere('provider_id', 'google-123');

    expect($user)->not->toBeNull();

    $this->assertAuthenticatedAs($user);

    expect($user->provider_name)->toBe('google');
    expect($user->email)->toBe('google@example.com');
    expect($user->provider_token)->toBe('fake-token');
    expect($user->provider_refresh_token)->toBe('fake-refresh-token');
    expect($user->hasRole('user'))->toBeTrue();
    expect($user->medicalFile()->count())->toBe(1);
    expect(User::query()->where('provider_id', 'google-123')->count())->toBe(1);
});
