<?php

use App\Models\Exams\Glucose;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = Glucose::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('glucose.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = Glucose::factory()->create(['glucose_level' => 12, 'glycated_hemoglobin' => 12, 'estimated_average_glucose' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    Glucose::factory()->for($record->medicalFile)->create(['glucose_level' => 12, 'glycated_hemoglobin' => 12, 'estimated_average_glucose' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    Glucose::factory()->create(['glucose_level' => 12, 'glycated_hemoglobin' => 12, 'estimated_average_glucose' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('glucose.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('glucoses.data', 1)
        ->where('glucoses.data.0.id', $record->id)
    );
})->with(['glucose_level', 'glycated_hemoglobin', 'estimated_average_glucose']);

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

        Glucose::factory()->for($medicalFile)->create([
            'glucose_level' => 120,
            'glycated_hemoglobin' => 5.6,
            'estimated_average_glucose' => 120,
            'report_date' => now()->toDateString(),
        ]);

        Glucose::factory()->for($medicalFile)->create([
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

        $glucose = Glucose::factory()->for($medicalFile)->create([
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

        $glucose = Glucose::factory()->for($medicalFile)->create([
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

        $glucose = Glucose::factory()->for($medicalFile)->create([
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

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('glucose.store'), [])
        ->assertSessionHasErrors(['glucose_level', 'glycated_hemoglobin', 'estimated_average_glucose', 'report_date']);

    $this->assertDatabaseCount('glucoses', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = Glucose::factory()->create(['glucose_level' => 20, 'glycated_hemoglobin' => 20, 'estimated_average_glucose' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['glucose_level', 'glycated_hemoglobin', 'estimated_average_glucose', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('glucose.store') : route('glucose.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('glucoses', 1);
    $this->assertDatabaseHas('glucoses', $original);
})->with([
    'negative glucose_level' => ['glucose_level', -1, 'The glucose level field must be at least 0.'],
    'non-numeric glucose_level' => ['glucose_level', 'invalid', 'The glucose level field must be a number.'],
    'negative glycated_hemoglobin' => ['glycated_hemoglobin', -1, 'The glycated hemoglobin field must be at least 0.'],
    'non-numeric glycated_hemoglobin' => ['glycated_hemoglobin', 'invalid', 'The glycated hemoglobin field must be a number.'],
    'negative estimated_average_glucose' => ['estimated_average_glucose', -1, 'The estimated average glucose field must be at least 0.'],
    'non-numeric estimated_average_glucose' => ['estimated_average_glucose', 'invalid', 'The estimated average glucose field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['glucose_level' => 0, 'glycated_hemoglobin' => 0, 'estimated_average_glucose' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('glucose.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('glucose.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('glucoses', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('glucoses', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = Glucose::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['glucose_level' => 20, 'glycated_hemoglobin' => 20, 'estimated_average_glucose' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('glucose.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('glucose.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('glucoses', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('glucose.'.$action, 999), ['glucose_level' => 20, 'glycated_hemoglobin' => 20, 'estimated_average_glucose' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = Glucose::factory()->create(['glucose_level' => 80]);
    Glucose::factory()->for($record->medicalFile)->create(['glucose_level' => 20]);
    Glucose::factory()->create(['glucose_level' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('glucose.index', ['sort_by' => 'glucose_level', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('glucoses.data', 1)
        ->where('glucoses.total', 2)
        ->where('glucoses.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = Glucose::factory()->create([$field => '2026-08-10 12:00:00']);
    Glucose::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    Glucose::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('glucose.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('glucoses.data', 1)
        ->where('glucoses.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = Glucose::factory()->create(['glucose_level' => 20, 'glycated_hemoglobin' => 20, 'estimated_average_glucose' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.Glucose::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('glucose.store') : route('glucose.'.$action, $record);

        $this->from(route('glucose.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['glucose_level', 'glycated_hemoglobin', 'estimated_average_glucose', 'report_date']), 'glucose_level' => 30])
            ->assertRedirect(route('glucose.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('glucoses', 1);
        $this->assertDatabaseHas('glucoses', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = Glucose::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
