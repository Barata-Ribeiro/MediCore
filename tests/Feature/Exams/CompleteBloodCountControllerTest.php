<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of CompleteBloodCountController', function () {
    $componentName = 'exams/complete-blood-count/index';

    it('should return empty data if no complete blood count exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('complete-blood-count.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->has('completeBloodCounts.data', 0)
        );
    });

    it('should return successful response with complete blood count data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->completeBloodCounts()->create([
            'hematocrit' => 40,
            'hemoglobin' => 14,
            'red_blood_cell_count' => 5,
            'mean_corpuscular_volume' => 90,
            'mean_corpuscular_hemoglobin' => 30,
            'mean_corpuscular_hemoglobin_concentration' => 33,
            'red_blood_cell_distribution_width' => 13,
            'leukocyte_count' => 7,
            'rod_neutrophil_count' => 0.5,
            'segmented_neutrophil_count' => 3.5,
            'lymphocyte_count' => 2,
            'monocyte_count' => 0.2,
            'eosinophil_count' => 0.1,
            'basophil_count' => 0.05,
            'metamyelocyte_count' => 0,
            'promyelocyte_count' => 0,
            'atypical_cell_count' => 0,
            'platelet_count' => 200,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->completeBloodCounts()->create([
            'hematocrit' => 42,
            'hemoglobin' => 15,
            'red_blood_cell_count' => 6,
            'mean_corpuscular_volume' => 92,
            'mean_corpuscular_hemoglobin' => 31,
            'mean_corpuscular_hemoglobin_concentration' => 34,
            'red_blood_cell_distribution_width' => 14,
            'leukocyte_count' => 8,
            'rod_neutrophil_count' => 0.6,
            'segmented_neutrophil_count' => 4,
            'lymphocyte_count' => 2.2,
            'monocyte_count' => 0.3,
            'eosinophil_count' => 0.2,
            'basophil_count' => 0.06,
            'metamyelocyte_count' => 0,
            'promyelocyte_count' => 0,
            'atypical_cell_count' => 0,
            'platelet_count' => 250,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('complete-blood-count.index'));

        $today = now()->startOfDay()->toISOString();
        $thirtyDaysAgo = now()->subDays(30)->startOfDay()->toISOString();

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('completeBloodCounts.data', 2)
            ->has('completeBloodCounts.data.0', fn (AssertableInertia $item) => $item
                ->where('hematocrit', 40)
                ->where('hemoglobin', 14)
                ->where('red_blood_cell_count', 5)
                ->where('platelet_count', 200)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('completeBloodCounts.data.1', fn (AssertableInertia $item) => $item
                ->where('hematocrit', 42)
                ->where('hemoglobin', 15)
                ->where('red_blood_cell_count', 6)
                ->where('platelet_count', 250)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('complete-blood-count.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of CompleteBloodCountController', function () {
    $componentName = 'exams/complete-blood-count/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('complete-blood-count.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName));
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('complete-blood-count.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of CompleteBloodCountController', function () {
    it('should store a new complete blood count record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'hematocrit' => 40,
            'hemoglobin' => 14,
            'red_blood_cell_count' => 5,
            'mean_corpuscular_volume' => 90,
            'mean_corpuscular_hemoglobin' => 30,
            'mean_corpuscular_hemoglobin_concentration' => 33,
            'red_blood_cell_distribution_width' => 13,
            'leukocyte_count' => 7,
            'rod_neutrophil_count' => 0.5,
            'segmented_neutrophil_count' => 3.5,
            'lymphocyte_count' => 2,
            'monocyte_count' => 0.2,
            'eosinophil_count' => 0.1,
            'basophil_count' => 0.05,
            'metamyelocyte_count' => 0,
            'promyelocyte_count' => 0,
            'atypical_cell_count' => 0,
            'platelet_count' => 200,
            'report_date' => now()->toDateString(),
            'medical_file_id' => $user->medicalFile->id,
        ];

        $response = $this->actingAs($user)->post(route('complete-blood-count.store'), $data);

        $response->assertRedirect(route('complete-blood-count.index'));
        $this->assertDatabaseHas('complete_blood_counts', $data);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('complete-blood-count.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of CompleteBloodCountController', function () {
    $componentName = 'exams/complete-blood-count/edit';

    it('should return the edit view with complete blood count data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $completeBloodCount = $medicalFile->completeBloodCounts()->create([
            'hematocrit' => 40,
            'hemoglobin' => 14,
            'red_blood_cell_count' => 5,
            'mean_corpuscular_volume' => 90,
            'mean_corpuscular_hemoglobin' => 30,
            'mean_corpuscular_hemoglobin_concentration' => 33,
            'red_blood_cell_distribution_width' => 13,
            'leukocyte_count' => 7,
            'rod_neutrophil_count' => 0.5,
            'segmented_neutrophil_count' => 3.5,
            'lymphocyte_count' => 2,
            'monocyte_count' => 0.2,
            'eosinophil_count' => 0.1,
            'basophil_count' => 0.05,
            'metamyelocyte_count' => 0,
            'promyelocyte_count' => 0,
            'atypical_cell_count' => 0,
            'platelet_count' => 200,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('complete-blood-count.edit', $completeBloodCount));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('completeBloodCount', fn (AssertableInertia $item) => $item
                ->where('hematocrit', 40)
                ->where('hemoglobin', 14)
                ->where('red_blood_cell_count', 5)
                ->where('platelet_count', 200)
                ->where('report_date', now()->startOfDay()->toISOString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('complete-blood-count.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of CompleteBloodCountController', function () {
    it('should update the complete blood count record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $completeBloodCount = $medicalFile->completeBloodCounts()->create([
            'hematocrit' => 40,
            'hemoglobin' => 14,
            'red_blood_cell_count' => 5,
            'mean_corpuscular_volume' => 90,
            'mean_corpuscular_hemoglobin' => 30,
            'mean_corpuscular_hemoglobin_concentration' => 33,
            'red_blood_cell_distribution_width' => 13,
            'leukocyte_count' => 7,
            'rod_neutrophil_count' => 0.5,
            'segmented_neutrophil_count' => 3.5,
            'lymphocyte_count' => 2,
            'monocyte_count' => 0.2,
            'eosinophil_count' => 0.1,
            'basophil_count' => 0.05,
            'metamyelocyte_count' => 0,
            'promyelocyte_count' => 0,
            'atypical_cell_count' => 0,
            'platelet_count' => 200,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'hematocrit' => 42,
            'hemoglobin' => 15,
            'red_blood_cell_count' => 6,
            'mean_corpuscular_volume' => 92,
            'mean_corpuscular_hemoglobin' => 31,
            'mean_corpuscular_hemoglobin_concentration' => 34,
            'red_blood_cell_distribution_width' => 14,
            'leukocyte_count' => 8,
            'rod_neutrophil_count' => 0.6,
            'segmented_neutrophil_count' => 4,
            'lymphocyte_count' => 2.2,
            'monocyte_count' => 0.3,
            'eosinophil_count' => 0.2,
            'basophil_count' => 0.06,
            'metamyelocyte_count' => 0,
            'promyelocyte_count' => 0,
            'atypical_cell_count' => 0,
            'platelet_count' => 250,
            'report_date' => now()->subDays(30)->toDateString(),
            'medical_file_id' => $medicalFile->id,
        ];

        $response = $this->actingAs($user)->put(route('complete-blood-count.update', $completeBloodCount), $updatedData);

        $response->assertRedirect(route('complete-blood-count.index'));
        $this->assertDatabaseHas('complete_blood_counts', [...$updatedData, 'id' => $completeBloodCount->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('complete-blood-count.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of CompleteBloodCountController', function () {
    it('should delete the complete blood count record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $completeBloodCount = $medicalFile->completeBloodCounts()->create([
            'hematocrit' => 40,
            'hemoglobin' => 14,
            'red_blood_cell_count' => 5,
            'mean_corpuscular_volume' => 90,
            'mean_corpuscular_hemoglobin' => 30,
            'mean_corpuscular_hemoglobin_concentration' => 33,
            'red_blood_cell_distribution_width' => 13,
            'leukocyte_count' => 7,
            'rod_neutrophil_count' => 0.5,
            'segmented_neutrophil_count' => 3.5,
            'lymphocyte_count' => 2,
            'monocyte_count' => 0.2,
            'eosinophil_count' => 0.1,
            'basophil_count' => 0.05,
            'metamyelocyte_count' => 0,
            'promyelocyte_count' => 0,
            'atypical_cell_count' => 0,
            'platelet_count' => 200,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('complete-blood-count.destroy', $completeBloodCount));

        $response->assertRedirect(route('complete-blood-count.index'));
        $this->assertDatabaseMissing('complete_blood_counts', ['id' => $completeBloodCount->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('complete-blood-count.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
