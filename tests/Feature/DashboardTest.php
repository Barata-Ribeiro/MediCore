<?php

use App\Models\Exams\TgoAndTgp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia;

test('dashboard includes only the authenticated user TGO and TGP records in totals', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    TgoAndTgp::factory()->count(2)->for($medicalFile)->create();
    TgoAndTgp::factory()->create();

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->where('data.exams.tgo_and_tgps_count', 2)
        ->where('data.exams.total', 2)
        ->where('lang.main.menu.sidebar_items.exams_items.tgo_and_tgp', 'TGO and TGP')
        ->missing('data.medicalFile.tgo_and_tgps_count')
        );
});

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('dashboard')
        ->has('date', fn (AssertableInertia $date) => $date
            ->has('now')
            ->has('displayDate')
            ->has('greeting')
        )
    );
});

test('dashboard route uses the dashboard service data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('dashboard')
        ->has('data', fn (AssertableInertia $data) => $data
            ->has('profile')
            ->has('medicalFile')
            ->missing('data.medicalFile.complete_blood_counts_count')
            ->missing('data.medicalFile.glucoses_count')
            ->missing('data.medicalFile.lipid_profiles_count')
            ->missing('data.medicalFile.total_proteins_and_fractions_count')
            ->missing('data.medicalFile.ultrasensitive_tshs_count')
            ->missing('data.medicalFile.urea_and_creatinines_count')
            ->missing('data.medicalFile.vitamin_d3s_count')
            ->missing('data.medicalFile.vitamin_b12s_count')
            ->has('exams', fn (AssertableInertia $exams) => $exams
                ->has('cbc_count')
                ->has('lipid_profiles_count')
                ->has('total_proteins_and_fractions_count')
                ->has('glucoses_count')
                ->has('ultrasensitive_tshs_count')
                ->has('tgo_and_tgps_count')
                ->has('urea_and_creatinines_count')
                ->has('vitamin_d3s_count')
                ->has('vitamin_b12s_count')
                ->has('total')
            )
        )
    );
});

test('dashboard route does not query the users table again for the authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    DB::flushQueryLog();
    DB::enableQueryLog();

    $response = $this->get(route('dashboard'));

    $userQueries = collect(DB::getQueryLog())
        ->pluck('query')
        ->filter(fn (string $query) => preg_match('/\bfrom\s+["`\[]?users["`\]]?\b/i', $query) === 1);

    DB::disableQueryLog();

    $response->assertSuccessful();

    expect($userQueries)->toHaveCount(0);
});
