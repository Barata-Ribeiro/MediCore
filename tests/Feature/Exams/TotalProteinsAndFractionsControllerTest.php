<?php

use App\Models\Exams\TotalProteinsAndFractions;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = TotalProteinsAndFractions::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('total-proteins-and-fractions.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = TotalProteinsAndFractions::factory()->create(['total_proteins' => 12, 'albumin' => 12, 'globulin' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    TotalProteinsAndFractions::factory()->for($record->medicalFile)->create(['total_proteins' => 12, 'albumin' => 12, 'globulin' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    TotalProteinsAndFractions::factory()->create(['total_proteins' => 12, 'albumin' => 12, 'globulin' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('total-proteins-and-fractions.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('totalProteinsAndFractions.data', 1)
        ->where('totalProteinsAndFractions.data.0.id', $record->id)
    );
})->with(['total_proteins', 'albumin', 'globulin']);

describe('tests for the "index" method of TotalProteinsAndFractionsController', function () {
    $componentName = 'exams/total-proteins-and-fractions/index';

    it('should return empty data if no total proteins and fractions record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('total-proteins-and-fractions.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.total_proteins_and_fractions_pages.index.head_title', 'Total Proteins and Fractions Exams')
                ->has('totalProteinsAndFractions.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with total proteins and fractions data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        TotalProteinsAndFractions::factory()->for($medicalFile)->create([
            'albumin' => 32.5,
            'globulin' => 1.1,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ]);

        TotalProteinsAndFractions::factory()->for($medicalFile)->create([
            'albumin' => 28.4,
            'globulin' => 0.9,
            'report_date' => now()->subDays(30)->toDateString(),
            'total_proteins' => 7.5,
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('total-proteins-and-fractions.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.total_proteins_and_fractions_pages.index.head_title', 'Total Proteins and Fractions Exams')
            ->has('totalProteinsAndFractions.data', 2)
            ->has('chartData')
            ->where('chartData.0.datasets.albumin.label', 'Albumin')
            ->where('chartData.0.datasets.globulin.label', 'Globulin')
            ->where('chartData.0.datasets.albumin_globulin_ratio.label', 'Albumin/Globulin Ratio')
            ->has('totalProteinsAndFractions.data.0', fn (AssertableInertia $item) => $item
                ->where('albumin', 32.5)
                ->where('globulin', 1.1)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('totalProteinsAndFractions.data.1', fn (AssertableInertia $item) => $item
                ->where('albumin', 28.4)
                ->where('globulin', 0.9)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('total-proteins-and-fractions.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of TotalProteinsAndFractionsController', function () {
    $componentName = 'exams/total-proteins-and-fractions/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('total-proteins-and-fractions.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.total_proteins_and_fractions_pages.create.head_title', 'Create Total Proteins and Fractions record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('total-proteins-and-fractions.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of TotalProteinsAndFractionsController', function () {
    it('should store a new total proteins and fractions record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'albumin' => 34.8,
            'globulin' => 1.2,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ];

        $response = $this->actingAs($user)->post(route('total-proteins-and-fractions.store'), $data);

        $response->assertRedirect(route('total-proteins-and-fractions.index'));
        $this->assertDatabaseHas('total_proteins_and_fractions', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('total-proteins-and-fractions.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of TotalProteinsAndFractionsController', function () {
    $componentName = 'exams/total-proteins-and-fractions/edit';

    it('should return the edit view with total proteins and fractions data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $totalProteinsAndFractions = TotalProteinsAndFractions::factory()->for($medicalFile)->create([
            'albumin' => 30.7,
            'globulin' => 1.0,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ]);

        $response = $this->actingAs($user)->get(route('total-proteins-and-fractions.edit', $totalProteinsAndFractions));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.total_proteins_and_fractions_pages.edit.head_title', 'Edit Total Proteins and Fractions record')
            ->has('totalProteinsAndFractions', fn (AssertableInertia $item) => $item
                ->where('albumin', 30.7)
                ->where('globulin', 1)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('total-proteins-and-fractions.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of TotalProteinsAndFractionsController', function () {
    it('should update the total proteins and fractions record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $totalProteinsAndFractions = TotalProteinsAndFractions::factory()->for($medicalFile)->create([
            'albumin' => 27.3,
            'globulin' => 0.8,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ]);

        $updatedData = [
            'albumin' => 38.1,
            'globulin' => 1.3,
            'report_date' => now()->subDays(15)->toDateString(),
            'total_proteins' => 7.5,
        ];

        $response = $this->actingAs($user)->put(route('total-proteins-and-fractions.update', $totalProteinsAndFractions), $updatedData);

        $response->assertRedirect(route('total-proteins-and-fractions.index'));
        $this->assertDatabaseHas('total_proteins_and_fractions', [...$updatedData, 'id' => $totalProteinsAndFractions->id]);
    });

    it('should not update a total proteins and fractions record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $totalProteinsAndFractions = TotalProteinsAndFractions::factory()->for($ownerMedicalFile)->create([
            'albumin' => 25.6,
            'globulin' => 0.7,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'albumin' => 41.0,
            'globulin' => 1.4,
            'report_date' => now()->subDays(10)->toDateString(),
            'total_proteins' => 7.5,
        ];

        $response = $this->from(route('total-proteins-and-fractions.index'))
            ->actingAs($anotherUser)
            ->put(route('total-proteins-and-fractions.update', $totalProteinsAndFractions), $updatedData);

        $response->assertRedirect(route('total-proteins-and-fractions.index'));
        $this->assertDatabaseHas('total_proteins_and_fractions', [
            'id' => $totalProteinsAndFractions->id,
            'albumin' => 25.6,
            'globulin' => 0.7,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('total-proteins-and-fractions.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of TotalProteinsAndFractionsController', function () {
    it('should delete the total proteins and fractions record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $totalProteinsAndFractions = TotalProteinsAndFractions::factory()->for($medicalFile)->create([
            'albumin' => 29.9,
            'globulin' => 1.1,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ]);

        $response = $this->actingAs($user)->delete(route('total-proteins-and-fractions.destroy', $totalProteinsAndFractions));

        $response->assertRedirect(route('total-proteins-and-fractions.index'));
        $this->assertDatabaseMissing('total_proteins_and_fractions', ['id' => $totalProteinsAndFractions->id]);
    });

    it('should not delete a total proteins and fractions record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $totalProteinsAndFractions = TotalProteinsAndFractions::factory()->for($ownerMedicalFile)->create([
            'albumin' => 24.2,
            'globulin' => 0.9,
            'report_date' => now()->toDateString(),
            'total_proteins' => 7.5,
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('total-proteins-and-fractions.index'))
            ->actingAs($anotherUser)
            ->delete(route('total-proteins-and-fractions.destroy', $totalProteinsAndFractions));

        $response->assertRedirect(route('total-proteins-and-fractions.index'));
        $this->assertDatabaseHas('total_proteins_and_fractions', ['id' => $totalProteinsAndFractions->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('total-proteins-and-fractions.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('total-proteins-and-fractions.store'), [])
        ->assertSessionHasErrors(['total_proteins', 'albumin', 'globulin', 'report_date']);

    $this->assertDatabaseCount('total_proteins_and_fractions', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = TotalProteinsAndFractions::factory()->create(['total_proteins' => 20, 'albumin' => 20, 'globulin' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['total_proteins', 'albumin', 'globulin', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('total-proteins-and-fractions.store') : route('total-proteins-and-fractions.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('total_proteins_and_fractions', 1);
    $this->assertDatabaseHas('total_proteins_and_fractions', $original);
})->with([
    'negative total_proteins' => ['total_proteins', -1, 'The total proteins field must be at least 0.'],
    'non-numeric total_proteins' => ['total_proteins', 'invalid', 'The total proteins field must be a number.'],
    'negative albumin' => ['albumin', -1, 'The albumin field must be at least 0.'],
    'non-numeric albumin' => ['albumin', 'invalid', 'The albumin field must be a number.'],
    'negative globulin' => ['globulin', -1, 'The globulin field must be at least 0.'],
    'non-numeric globulin' => ['globulin', 'invalid', 'The globulin field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['total_proteins' => 0, 'albumin' => 0, 'globulin' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('total-proteins-and-fractions.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('total-proteins-and-fractions.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('total_proteins_and_fractions', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('total_proteins_and_fractions', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = TotalProteinsAndFractions::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['total_proteins' => 20, 'albumin' => 20, 'globulin' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('total-proteins-and-fractions.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('total-proteins-and-fractions.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('total_proteins_and_fractions', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('total-proteins-and-fractions.'.$action, 999), ['total_proteins' => 20, 'albumin' => 20, 'globulin' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = TotalProteinsAndFractions::factory()->create(['total_proteins' => 80]);
    TotalProteinsAndFractions::factory()->for($record->medicalFile)->create(['total_proteins' => 20]);
    TotalProteinsAndFractions::factory()->create(['total_proteins' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('total-proteins-and-fractions.index', ['sort_by' => 'total_proteins', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('totalProteinsAndFractions.data', 1)
        ->where('totalProteinsAndFractions.total', 2)
        ->where('totalProteinsAndFractions.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = TotalProteinsAndFractions::factory()->create([$field => '2026-08-10 12:00:00']);
    TotalProteinsAndFractions::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    TotalProteinsAndFractions::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('total-proteins-and-fractions.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('totalProteinsAndFractions.data', 1)
        ->where('totalProteinsAndFractions.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = TotalProteinsAndFractions::factory()->create(['total_proteins' => 20, 'albumin' => 20, 'globulin' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.TotalProteinsAndFractions::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('total-proteins-and-fractions.store') : route('total-proteins-and-fractions.'.$action, $record);

        $this->from(route('total-proteins-and-fractions.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['total_proteins', 'albumin', 'globulin', 'report_date']), 'total_proteins' => 30])
            ->assertRedirect(route('total-proteins-and-fractions.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('total_proteins_and_fractions', 1);
        $this->assertDatabaseHas('total_proteins_and_fractions', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = TotalProteinsAndFractions::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
