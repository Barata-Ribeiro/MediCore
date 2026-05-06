<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user locale is applied to inertia responses', function () {
    $user = User::factory()->create(['locale' => 'pt_BR']);

    $this->actingAs($user)
        ->withHeader('CF-IPCountry', 'US')
        ->withHeader('Accept-Language', 'en-US,en;q=0.9')
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('lang="pt-BR"', false)
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/profile')
            ->where('auth.locale', 'pt_BR')
        );
});

test('guest locale is inferred from a brazil country header', function () {
    $this->withHeader('CF-IPCountry', 'BR')
        ->get(route('home'))
        ->assertOk()
        ->assertSee('lang="pt-BR"', false)
        ->assertInertia(fn (Assert $page) => $page
            ->component('welcome')
            ->where('auth.locale', 'pt_BR')
        );
});

test('guest locale is inferred from brazilian portuguese request language', function () {
    $this->withHeader('Accept-Language', 'pt-BR,pt;q=0.9,en;q=0.8')
        ->get(route('home'))
        ->assertOk()
        ->assertSee('lang="pt-BR"', false)
        ->assertInertia(fn (Assert $page) => $page
            ->component('welcome')
            ->where('auth.locale', 'pt_BR')
        );
});

test('guest locale falls back to english for non brazilian request locales', function () {
    config(['app.locale' => 'pt_BR']);

    $this->withHeader('Accept-Language', 'pt-PT,pt;q=0.9')
        ->get(route('home'))
        ->assertOk()
        ->assertSee('lang="en"', false)
        ->assertInertia(fn (Assert $page) => $page
            ->component('welcome')
            ->where('auth.locale', 'en')
        );
});

test('guest locale falls back to configured locale when the request has no locale hints', function () {
    config(['app.locale' => 'pt_BR']);

    $this->withHeader('Accept-Language', '')
        ->get(route('home'))
        ->assertOk()
        ->assertSee('lang="pt-BR"', false)
        ->assertInertia(fn (Assert $page) => $page
            ->component('welcome')
            ->where('auth.locale', 'pt_BR')
        );
});

test('locale can be updated', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->followingRedirects()
        ->from(route('profile.edit'))
        ->patch(route('locale.update'), ['locale' => 'pt_BR'])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/profile')
            ->where('auth.locale', 'pt_BR')
            ->hasFlash('toast.type', 'success')
            ->hasFlash('toast.message', 'Idioma atualizado com sucesso.')
        );

    expect($user->refresh()->locale)->toBe('pt_BR');
});

test('locale must be supported', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->from(route('profile.edit'))
        ->patch(route('locale.update'), ['locale' => 'es'])
        ->assertSessionHasErrors('locale')
        ->assertRedirect(route('profile.edit'));

    expect($user->refresh()->locale)->toBe('en');
});
