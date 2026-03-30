<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of LipidProfileController', function () {
    $componentName = 'exams/lipid-profile/index';

    it('should return empty data if no lipid profile exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('lipid-profile.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->has('lipidProfile.data', 0)
        );
    });

    it('should return successful response with lipid profile data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->lipidProfile()->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->lipidProfile()->create([
            'total_cholesterol' => 120,
            'hdl_cholesterol' => 60,
            'ldl_cholesterol' => 30,
            'vldl_cholesterol' => 15,
            'triglycerides' => 90,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('lipid-profile.index'));

        $today = now()->startOfDay()->toISOString();
        $thirtyDaysAgo = now()->subDays(30)->startOfDay()->toISOString();

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('lipidProfile.data', 2)
            ->has('lipidProfile.data.0', fn (AssertableInertia $item) => $item
                ->where('total_cholesterol', 100)
                ->where('hdl_cholesterol', 50)
                ->where('ldl_cholesterol', 20)
                ->where('vldl_cholesterol', 10)
                ->where('triglycerides', 80)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('lipidProfile.data.1', fn (AssertableInertia $item) => $item
                ->where('total_cholesterol', 120)
                ->where('hdl_cholesterol', 60)
                ->where('ldl_cholesterol', 30)
                ->where('vldl_cholesterol', 15)
                ->where('triglycerides', 90)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('lipid-profile.index'));

        $response->assertRedirect(route('login'));
    });
});
