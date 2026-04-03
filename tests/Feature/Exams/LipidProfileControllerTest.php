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
                ->has('lipidProfiles.data', 0)
        );
    });

    it('should return successful response with lipid profile data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->lipidProfiles()->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->lipidProfiles()->create([
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
            ->has('lipidProfiles.data', 2)
            ->has('lipidProfiles.data.0', fn (AssertableInertia $item) => $item
                ->where('total_cholesterol', 100)
                ->where('hdl_cholesterol', 50)
                ->where('ldl_cholesterol', 20)
                ->where('vldl_cholesterol', 10)
                ->where('triglycerides', 80)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('lipidProfiles.data.1', fn (AssertableInertia $item) => $item
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

describe('tests for the "create" method of LipidProfileController', function () {
    $componentName = 'exams/lipid-profile/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('lipid-profile.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName));
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('lipid-profile.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of LipidProfileController', function () {
    it('should store a new lipid profile record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('lipid-profile.store'), $data);

        $response->assertRedirect(route('lipid-profile.index'));
        $this->assertDatabaseHas('lipid_profiles', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('lipid-profile.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of LipidProfileController', function () {
    $componentName = 'exams/lipid-profile/edit';

    it('should return the edit view with lipid profile data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $lipidProfile = $medicalFile->lipidProfiles()->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('lipid-profile.edit', $lipidProfile));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('lipidProfile', fn (AssertableInertia $item) => $item
                ->where('total_cholesterol', 100)
                ->where('hdl_cholesterol', 50)
                ->where('ldl_cholesterol', 20)
                ->where('vldl_cholesterol', 10)
                ->where('triglycerides', 80)
                ->where('report_date', now()->startOfDay()->toISOString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('lipid-profile.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of LipidProfileController', function () {
    it('should update the lipid profile record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $lipidProfile = $medicalFile->lipidProfiles()->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'total_cholesterol' => 120,
            'hdl_cholesterol' => 60,
            'ldl_cholesterol' => 30,
            'vldl_cholesterol' => 15,
            'triglycerides' => 90,
            'report_date' => now()->subDays(30)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('lipid-profile.update', $lipidProfile), $updatedData);

        $response->assertRedirect(route('lipid-profile.index'));
        $this->assertDatabaseHas('lipid_profiles', [...$updatedData, 'id' => $lipidProfile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('lipid-profile.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of LipidProfileController', function () {
    it('should delete the lipid profile record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $lipidProfile = $medicalFile->lipidProfiles()->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('lipid-profile.destroy', $lipidProfile));

        $response->assertRedirect(route('lipid-profile.index'));
        $this->assertDatabaseMissing('lipid_profiles', ['id' => $lipidProfile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('lipid-profile.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
