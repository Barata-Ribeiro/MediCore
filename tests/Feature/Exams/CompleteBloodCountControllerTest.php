<?php

use App\Models\Exams\CompleteBloodCount;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = CompleteBloodCount::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('complete-blood-count.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = CompleteBloodCount::factory()->create(['hematocrit' => 12, 'hemoglobin' => 12, 'red_blood_cell_count' => 12, 'mean_corpuscular_volume' => 12, 'mean_corpuscular_hemoglobin' => 12, 'mean_corpuscular_hemoglobin_concentration' => 12, 'red_blood_cell_distribution_width' => 12, 'leukocyte_count' => 12, 'rod_neutrophil_count' => 12, 'segmented_neutrophil_count' => 12, 'lymphocyte_count' => 12, 'monocyte_count' => 12, 'eosinophil_count' => 12, 'basophil_count' => 12, 'metamyelocyte_count' => 12, 'promyelocyte_count' => 12, 'atypical_cell_count' => 12, 'platelet_count' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    CompleteBloodCount::factory()->for($record->medicalFile)->create(['hematocrit' => 12, 'hemoglobin' => 12, 'red_blood_cell_count' => 12, 'mean_corpuscular_volume' => 12, 'mean_corpuscular_hemoglobin' => 12, 'mean_corpuscular_hemoglobin_concentration' => 12, 'red_blood_cell_distribution_width' => 12, 'leukocyte_count' => 12, 'rod_neutrophil_count' => 12, 'segmented_neutrophil_count' => 12, 'lymphocyte_count' => 12, 'monocyte_count' => 12, 'eosinophil_count' => 12, 'basophil_count' => 12, 'metamyelocyte_count' => 12, 'promyelocyte_count' => 12, 'atypical_cell_count' => 12, 'platelet_count' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    CompleteBloodCount::factory()->create(['hematocrit' => 12, 'hemoglobin' => 12, 'red_blood_cell_count' => 12, 'mean_corpuscular_volume' => 12, 'mean_corpuscular_hemoglobin' => 12, 'mean_corpuscular_hemoglobin_concentration' => 12, 'red_blood_cell_distribution_width' => 12, 'leukocyte_count' => 12, 'rod_neutrophil_count' => 12, 'segmented_neutrophil_count' => 12, 'lymphocyte_count' => 12, 'monocyte_count' => 12, 'eosinophil_count' => 12, 'basophil_count' => 12, 'metamyelocyte_count' => 12, 'promyelocyte_count' => 12, 'atypical_cell_count' => 12, 'platelet_count' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('complete-blood-count.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('completeBloodCounts.data', 1)
        ->where('completeBloodCounts.data.0.id', $record->id)
    );
})->with(['hematocrit', 'hemoglobin', 'red_blood_cell_count', 'mean_corpuscular_volume', 'mean_corpuscular_hemoglobin', 'mean_corpuscular_hemoglobin_concentration', 'red_blood_cell_distribution_width', 'leukocyte_count', 'rod_neutrophil_count', 'segmented_neutrophil_count', 'lymphocyte_count', 'monocyte_count', 'eosinophil_count', 'basophil_count', 'metamyelocyte_count', 'promyelocyte_count', 'atypical_cell_count', 'platelet_count']);

describe('tests for the "index" method of CompleteBloodCountController', function () {
    $componentName = 'exams/complete-blood-count/index';

    it('should return empty data if no complete blood count exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('complete-blood-count.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.complete_blood_count_pages.index.head_title', 'Complete Blood Count')
                ->has('completeBloodCounts.data', 0)
        );
    });

    it('should return successful response with complete blood count data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        CompleteBloodCount::factory()->for($medicalFile)->create([
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

        CompleteBloodCount::factory()->for($medicalFile)->create([
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

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

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
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.complete_blood_count_pages.create.head_title', 'Create Complete Blood Count record'));
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
        ];

        $response = $this->actingAs($user)->post(route('complete-blood-count.store'), $data);

        $response->assertRedirect(route('complete-blood-count.index'));
        $this->assertDatabaseHas('complete_blood_counts', [...$data, 'medical_file_id' => $user->medicalFile->id]);
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

        $completeBloodCount = CompleteBloodCount::factory()->for($medicalFile)->create([
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
            ->where('lang.complete_blood_count_pages.edit.head_title', 'Edit Complete Blood Count record')
            ->has('completeBloodCount', fn (AssertableInertia $item) => $item
                ->where('hematocrit', 40)
                ->where('hemoglobin', 14)
                ->where('red_blood_cell_count', 5)
                ->where('platelet_count', 200)
                ->where('report_date', now()->toDateString())
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

        $completeBloodCount = CompleteBloodCount::factory()->for($medicalFile)->create([
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

        $completeBloodCount = CompleteBloodCount::factory()->for($medicalFile)->create([
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

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('complete-blood-count.store'), [])
        ->assertSessionHasErrors(['hematocrit', 'hemoglobin', 'red_blood_cell_count', 'mean_corpuscular_volume', 'mean_corpuscular_hemoglobin', 'mean_corpuscular_hemoglobin_concentration', 'red_blood_cell_distribution_width', 'leukocyte_count', 'rod_neutrophil_count', 'segmented_neutrophil_count', 'lymphocyte_count', 'monocyte_count', 'eosinophil_count', 'basophil_count', 'metamyelocyte_count', 'promyelocyte_count', 'atypical_cell_count', 'platelet_count', 'report_date']);

    $this->assertDatabaseCount('complete_blood_counts', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = CompleteBloodCount::factory()->create(['hematocrit' => 20, 'hemoglobin' => 20, 'red_blood_cell_count' => 20, 'mean_corpuscular_volume' => 20, 'mean_corpuscular_hemoglobin' => 20, 'mean_corpuscular_hemoglobin_concentration' => 20, 'red_blood_cell_distribution_width' => 20, 'leukocyte_count' => 20, 'rod_neutrophil_count' => 20, 'segmented_neutrophil_count' => 20, 'lymphocyte_count' => 20, 'monocyte_count' => 20, 'eosinophil_count' => 20, 'basophil_count' => 20, 'metamyelocyte_count' => 20, 'promyelocyte_count' => 20, 'atypical_cell_count' => 20, 'platelet_count' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['hematocrit', 'hemoglobin', 'red_blood_cell_count', 'mean_corpuscular_volume', 'mean_corpuscular_hemoglobin', 'mean_corpuscular_hemoglobin_concentration', 'red_blood_cell_distribution_width', 'leukocyte_count', 'rod_neutrophil_count', 'segmented_neutrophil_count', 'lymphocyte_count', 'monocyte_count', 'eosinophil_count', 'basophil_count', 'metamyelocyte_count', 'promyelocyte_count', 'atypical_cell_count', 'platelet_count', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('complete-blood-count.store') : route('complete-blood-count.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('complete_blood_counts', 1);
    $this->assertDatabaseHas('complete_blood_counts', $original);
})->with([
    'negative hematocrit' => ['hematocrit', -1, 'The hematocrit field must be at least 0.'],
    'non-numeric hematocrit' => ['hematocrit', 'invalid', 'The hematocrit field must be a number.'],
    'negative hemoglobin' => ['hemoglobin', -1, 'The hemoglobin field must be at least 0.'],
    'non-numeric hemoglobin' => ['hemoglobin', 'invalid', 'The hemoglobin field must be a number.'],
    'negative red_blood_cell_count' => ['red_blood_cell_count', -1, 'The red blood cell count field must be at least 0.'],
    'non-numeric red_blood_cell_count' => ['red_blood_cell_count', 'invalid', 'The red blood cell count field must be a number.'],
    'negative mean_corpuscular_volume' => ['mean_corpuscular_volume', -1, 'The mean corpuscular volume field must be at least 0.'],
    'non-numeric mean_corpuscular_volume' => ['mean_corpuscular_volume', 'invalid', 'The mean corpuscular volume field must be a number.'],
    'negative mean_corpuscular_hemoglobin' => ['mean_corpuscular_hemoglobin', -1, 'The mean corpuscular hemoglobin field must be at least 0.'],
    'non-numeric mean_corpuscular_hemoglobin' => ['mean_corpuscular_hemoglobin', 'invalid', 'The mean corpuscular hemoglobin field must be a number.'],
    'negative mean_corpuscular_hemoglobin_concentration' => ['mean_corpuscular_hemoglobin_concentration', -1, 'The mean corpuscular hemoglobin concentration field must be at least 0.'],
    'non-numeric mean_corpuscular_hemoglobin_concentration' => ['mean_corpuscular_hemoglobin_concentration', 'invalid', 'The mean corpuscular hemoglobin concentration field must be a number.'],
    'negative red_blood_cell_distribution_width' => ['red_blood_cell_distribution_width', -1, 'The red blood cell distribution width field must be at least 0.'],
    'non-numeric red_blood_cell_distribution_width' => ['red_blood_cell_distribution_width', 'invalid', 'The red blood cell distribution width field must be a number.'],
    'negative leukocyte_count' => ['leukocyte_count', -1, 'The leukocyte count field must be at least 0.'],
    'non-numeric leukocyte_count' => ['leukocyte_count', 'invalid', 'The leukocyte count field must be a number.'],
    'negative rod_neutrophil_count' => ['rod_neutrophil_count', -1, 'The rod neutrophil count field must be at least 0.'],
    'non-numeric rod_neutrophil_count' => ['rod_neutrophil_count', 'invalid', 'The rod neutrophil count field must be a number.'],
    'negative segmented_neutrophil_count' => ['segmented_neutrophil_count', -1, 'The segmented neutrophil count field must be at least 0.'],
    'non-numeric segmented_neutrophil_count' => ['segmented_neutrophil_count', 'invalid', 'The segmented neutrophil count field must be a number.'],
    'negative lymphocyte_count' => ['lymphocyte_count', -1, 'The lymphocyte count field must be at least 0.'],
    'non-numeric lymphocyte_count' => ['lymphocyte_count', 'invalid', 'The lymphocyte count field must be a number.'],
    'negative monocyte_count' => ['monocyte_count', -1, 'The monocyte count field must be at least 0.'],
    'non-numeric monocyte_count' => ['monocyte_count', 'invalid', 'The monocyte count field must be a number.'],
    'negative eosinophil_count' => ['eosinophil_count', -1, 'The eosinophil count field must be at least 0.'],
    'non-numeric eosinophil_count' => ['eosinophil_count', 'invalid', 'The eosinophil count field must be a number.'],
    'negative basophil_count' => ['basophil_count', -1, 'The basophil count field must be at least 0.'],
    'non-numeric basophil_count' => ['basophil_count', 'invalid', 'The basophil count field must be a number.'],
    'negative metamyelocyte_count' => ['metamyelocyte_count', -1, 'The metamyelocyte count field must be at least 0.'],
    'non-numeric metamyelocyte_count' => ['metamyelocyte_count', 'invalid', 'The metamyelocyte count field must be a number.'],
    'negative promyelocyte_count' => ['promyelocyte_count', -1, 'The promyelocyte count field must be at least 0.'],
    'non-numeric promyelocyte_count' => ['promyelocyte_count', 'invalid', 'The promyelocyte count field must be a number.'],
    'negative atypical_cell_count' => ['atypical_cell_count', -1, 'The atypical cell count field must be at least 0.'],
    'non-numeric atypical_cell_count' => ['atypical_cell_count', 'invalid', 'The atypical cell count field must be a number.'],
    'negative platelet_count' => ['platelet_count', -1, 'The platelet count field must be at least 0.'],
    'non-numeric platelet_count' => ['platelet_count', 'invalid', 'The platelet count field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['hematocrit' => 0, 'hemoglobin' => 0, 'red_blood_cell_count' => 0, 'mean_corpuscular_volume' => 0, 'mean_corpuscular_hemoglobin' => 0, 'mean_corpuscular_hemoglobin_concentration' => 0, 'red_blood_cell_distribution_width' => 0, 'leukocyte_count' => 0, 'rod_neutrophil_count' => 0, 'segmented_neutrophil_count' => 0, 'lymphocyte_count' => 0, 'monocyte_count' => 0, 'eosinophil_count' => 0, 'basophil_count' => 0, 'metamyelocyte_count' => 0, 'promyelocyte_count' => 0, 'atypical_cell_count' => 0, 'platelet_count' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('complete-blood-count.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('complete-blood-count.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('complete_blood_counts', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('complete_blood_counts', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = CompleteBloodCount::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['hematocrit' => 20, 'hemoglobin' => 20, 'red_blood_cell_count' => 20, 'mean_corpuscular_volume' => 20, 'mean_corpuscular_hemoglobin' => 20, 'mean_corpuscular_hemoglobin_concentration' => 20, 'red_blood_cell_distribution_width' => 20, 'leukocyte_count' => 20, 'rod_neutrophil_count' => 20, 'segmented_neutrophil_count' => 20, 'lymphocyte_count' => 20, 'monocyte_count' => 20, 'eosinophil_count' => 20, 'basophil_count' => 20, 'metamyelocyte_count' => 20, 'promyelocyte_count' => 20, 'atypical_cell_count' => 20, 'platelet_count' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('complete-blood-count.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('complete-blood-count.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('complete_blood_counts', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('complete-blood-count.'.$action, 999), ['hematocrit' => 20, 'hemoglobin' => 20, 'red_blood_cell_count' => 20, 'mean_corpuscular_volume' => 20, 'mean_corpuscular_hemoglobin' => 20, 'mean_corpuscular_hemoglobin_concentration' => 20, 'red_blood_cell_distribution_width' => 20, 'leukocyte_count' => 20, 'rod_neutrophil_count' => 20, 'segmented_neutrophil_count' => 20, 'lymphocyte_count' => 20, 'monocyte_count' => 20, 'eosinophil_count' => 20, 'basophil_count' => 20, 'metamyelocyte_count' => 20, 'promyelocyte_count' => 20, 'atypical_cell_count' => 20, 'platelet_count' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = CompleteBloodCount::factory()->create(['hematocrit' => 80]);
    CompleteBloodCount::factory()->for($record->medicalFile)->create(['hematocrit' => 20]);
    CompleteBloodCount::factory()->create(['hematocrit' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('complete-blood-count.index', ['sort_by' => 'hematocrit', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('completeBloodCounts.data', 1)
        ->where('completeBloodCounts.total', 2)
        ->where('completeBloodCounts.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = CompleteBloodCount::factory()->create([$field => '2026-08-10 12:00:00']);
    CompleteBloodCount::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    CompleteBloodCount::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('complete-blood-count.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('completeBloodCounts.data', 1)
        ->where('completeBloodCounts.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = CompleteBloodCount::factory()->create(['hematocrit' => 20, 'hemoglobin' => 20, 'red_blood_cell_count' => 20, 'mean_corpuscular_volume' => 20, 'mean_corpuscular_hemoglobin' => 20, 'mean_corpuscular_hemoglobin_concentration' => 20, 'red_blood_cell_distribution_width' => 20, 'leukocyte_count' => 20, 'rod_neutrophil_count' => 20, 'segmented_neutrophil_count' => 20, 'lymphocyte_count' => 20, 'monocyte_count' => 20, 'eosinophil_count' => 20, 'basophil_count' => 20, 'metamyelocyte_count' => 20, 'promyelocyte_count' => 20, 'atypical_cell_count' => 20, 'platelet_count' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.CompleteBloodCount::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('complete-blood-count.store') : route('complete-blood-count.'.$action, $record);

        $this->from(route('complete-blood-count.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['hematocrit', 'hemoglobin', 'red_blood_cell_count', 'mean_corpuscular_volume', 'mean_corpuscular_hemoglobin', 'mean_corpuscular_hemoglobin_concentration', 'red_blood_cell_distribution_width', 'leukocyte_count', 'rod_neutrophil_count', 'segmented_neutrophil_count', 'lymphocyte_count', 'monocyte_count', 'eosinophil_count', 'basophil_count', 'metamyelocyte_count', 'promyelocyte_count', 'atypical_cell_count', 'platelet_count', 'report_date']), 'hematocrit' => 30])
            ->assertRedirect(route('complete-blood-count.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('complete_blood_counts', 1);
        $this->assertDatabaseHas('complete_blood_counts', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = CompleteBloodCount::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
