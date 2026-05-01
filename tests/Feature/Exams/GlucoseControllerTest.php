<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of GlucoseController', function () {
    $componentName = 'exams/glucose/index';

    it('should return empty data if no glucose exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('glucose.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.glucose_pages.index.head_title', 'Glucose Exams')
                ->has('glucoses.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with glucose data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->glucoses()->create([
            'glucose_level' => 120,
            'glycated_hemoglobin' => 5.6,
            'estimated_average_glucose' => 120,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->glucoses()->create([
            'glucose_level' => 110,
            'glycated_hemoglobin' => 5.4,
            'estimated_average_glucose' => 110,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('glucose.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.glucose_pages.index.head_title', 'Glucose Exams')
            ->has('glucoses.data', 2)
            ->has('chartData')
            ->has('glucoses.data.0', fn (AssertableInertia $item) => $item->etc())
            ->has('glucoses.data.1', fn (AssertableInertia $item) => $item->etc())
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('glucose.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of GlucoseController', function () {
    $componentName = 'exams/glucose/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('glucose.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.glucose_pages.create.head_title', 'Create Glucose record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('glucose.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of GlucoseController', function () {
    it('should store a new glucose record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'glucose_level' => 130,
            'glycated_hemoglobin' => 6.0,
            'estimated_average_glucose' => 130,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('glucose.store'), $data);

        $response->assertRedirect(route('glucose.index'));
        $this->assertDatabaseHas('glucoses', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('glucose.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of GlucoseController', function () {
    $componentName = 'exams/glucose/edit';

    it('should return the edit view with glucose data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $glucose = $medicalFile->glucoses()->create([
            'glucose_level' => 120,
            'glycated_hemoglobin' => 5.6,
            'estimated_average_glucose' => 120,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('glucose.edit', $glucose));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.glucose_pages.edit.head_title', 'Edit Glucose record')
            ->has('glucose', fn (AssertableInertia $item) => $item->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('glucose.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of GlucoseController', function () {
    it('should update the glucose record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $glucose = $medicalFile->glucoses()->create([
            'report_date' => now()->toDateString(),
            'glucose_level' => 100,
            'glycated_hemoglobin' => 5.0,
            'estimated_average_glucose' => 100,
        ]);

        $updatedData = [
            'glucose_level' => 140,
            'glycated_hemoglobin' => 6.0,
            'estimated_average_glucose' => 140,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('glucose.update', $glucose), $updatedData);

        $response->assertRedirect(route('glucose.index'));
        $this->assertDatabaseHas('glucoses', [...$updatedData, 'id' => $glucose->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('glucose.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of GlucoseController', function () {
    it('should delete the glucose record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $glucose = $medicalFile->glucoses()->create([
            'report_date' => now()->toDateString(),
            'glucose_level' => 115,
            'glycated_hemoglobin' => 5.2,
            'estimated_average_glucose' => 115,
        ]);

        $response = $this->actingAs($user)->delete(route('glucose.destroy', $glucose));

        $response->assertRedirect(route('glucose.index'));
        $this->assertDatabaseMissing('glucoses', ['id' => $glucose->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('glucose.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
