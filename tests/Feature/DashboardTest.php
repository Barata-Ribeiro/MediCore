<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

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
            ->missing('data.medicalFile.ultrasensitive_tshs_count')
            ->missing('data.medicalFile.urea_and_creatinines_count')
            ->missing('data.medicalFile.vitamin_d3s_count')
            ->missing('data.medicalFile.vitamin_b12s_count')
            ->has('exams', fn (AssertableInertia $exams) => $exams
                ->has('cbc_count')
                ->has('lipid_profiles_count')
                ->has('glucoses_count')
                ->has('ultrasensitive_tshs_count')
                ->has('urea_and_creatinines_count')
                ->has('vitamin_d3s_count')
                ->has('vitamin_b12s_count')
                ->has('total')
            )
        )
    );
});
