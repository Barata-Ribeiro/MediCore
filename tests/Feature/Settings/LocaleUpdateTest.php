<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user locale is applied to inertia responses', function () {
    $user = User::factory()->create(['locale' => 'pt_BR']);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('lang="pt-BR"', false)
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/profile')
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
