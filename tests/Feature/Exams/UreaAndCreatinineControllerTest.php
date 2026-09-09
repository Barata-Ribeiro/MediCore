<?php

use App\Models\Exams\UreaAndCreatinine;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = UreaAndCreatinine::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('urea-and-creatinine.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = UreaAndCreatinine::factory()->create(['urea_level' => 12, 'creatinine_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    UreaAndCreatinine::factory()->for($record->medicalFile)->create(['urea_level' => 12, 'creatinine_level' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    UreaAndCreatinine::factory()->create(['urea_level' => 12, 'creatinine_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('urea-and-creatinine.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('ureaAndCreatinines.data', 1)
        ->where('ureaAndCreatinines.data.0.id', $record->id)
    );
})->with(['urea_level', 'creatinine_level']);

describe('tests for the "index" method of UreaAndCreatinineController', function () {
    $componentName = 'exams/urea-and-creatinine/index';

    it('should return empty data if no urea and creatinine record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.urea_and_creatinine_pages.index.head_title', 'Urea and Creatinine Exams')
                ->has('ureaAndCreatinines.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with urea and creatinine data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        UreaAndCreatinine::factory()->for($medicalFile)->create([
            'urea_level' => 32.5,
            'creatinine_level' => 1.1,
            'report_date' => now()->toDateString(),
        ]);

        UreaAndCreatinine::factory()->for($medicalFile)->create([
            'urea_level' => 28.4,
            'creatinine_level' => 0.9,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.urea_and_creatinine_pages.index.head_title', 'Urea and Creatinine Exams')
            ->has('ureaAndCreatinines.data', 2)
            ->has('chartData')
            ->where('chartData.0.datasets.urea_level.label', 'Urea Level')
            ->where('chartData.0.datasets.creatinine_level.label', 'Creatinine Level')
            ->where('chartData.0.datasets.urea_to_creatinine_ratio.label', 'Urea to Creatinine Ratio')
            ->has('ureaAndCreatinines.data.0', fn (AssertableInertia $item) => $item
                ->where('urea_level', 32.5)
                ->where('creatinine_level', 1.1)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('ureaAndCreatinines.data.1', fn (AssertableInertia $item) => $item
                ->where('urea_level', 28.4)
                ->where('creatinine_level', 0.9)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('urea-and-creatinine.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of UreaAndCreatinineController', function () {
    $componentName = 'exams/urea-and-creatinine/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.urea_and_creatinine_pages.create.head_title', 'Create Urea and Creatinine record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('urea-and-creatinine.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of UreaAndCreatinineController', function () {
    it('should store a new urea and creatinine record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'urea_level' => 34.8,
            'creatinine_level' => 1.2,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('urea-and-creatinine.store'), $data);

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('urea-and-creatinine.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of UreaAndCreatinineController', function () {
    $componentName = 'exams/urea-and-creatinine/edit';

    it('should return the edit view with urea and creatinine data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ureaAndCreatinine = UreaAndCreatinine::factory()->for($medicalFile)->create([
            'urea_level' => 30.7,
            'creatinine_level' => 1.0,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.edit', $ureaAndCreatinine));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.urea_and_creatinine_pages.edit.head_title', 'Edit Urea and Creatinine record')
            ->has('ureaAndCreatinine', fn (AssertableInertia $item) => $item
                ->where('urea_level', 30.7)
                ->where('creatinine_level', 1)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('urea-and-creatinine.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of UreaAndCreatinineController', function () {
    it('should update the urea and creatinine record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ureaAndCreatinine = UreaAndCreatinine::factory()->for($medicalFile)->create([
            'urea_level' => 27.3,
            'creatinine_level' => 0.8,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'urea_level' => 38.1,
            'creatinine_level' => 1.3,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('urea-and-creatinine.update', $ureaAndCreatinine), $updatedData);

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', [...$updatedData, 'id' => $ureaAndCreatinine->id]);
    });

    it('should not update a urea and creatinine record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ureaAndCreatinine = UreaAndCreatinine::factory()->for($ownerMedicalFile)->create([
            'urea_level' => 25.6,
            'creatinine_level' => 0.7,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'urea_level' => 41.0,
            'creatinine_level' => 1.4,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('urea-and-creatinine.index'))
            ->actingAs($anotherUser)
            ->put(route('urea-and-creatinine.update', $ureaAndCreatinine), $updatedData);

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', [
            'id' => $ureaAndCreatinine->id,
            'urea_level' => 25.6,
            'creatinine_level' => 0.7,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('urea-and-creatinine.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of UreaAndCreatinineController', function () {
    it('should delete the urea and creatinine record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ureaAndCreatinine = UreaAndCreatinine::factory()->for($medicalFile)->create([
            'urea_level' => 29.9,
            'creatinine_level' => 1.1,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('urea-and-creatinine.destroy', $ureaAndCreatinine));

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseMissing('urea_and_creatinines', ['id' => $ureaAndCreatinine->id]);
    });

    it('should not delete a urea and creatinine record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ureaAndCreatinine = UreaAndCreatinine::factory()->for($ownerMedicalFile)->create([
            'urea_level' => 24.2,
            'creatinine_level' => 0.9,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('urea-and-creatinine.index'))
            ->actingAs($anotherUser)
            ->delete(route('urea-and-creatinine.destroy', $ureaAndCreatinine));

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', ['id' => $ureaAndCreatinine->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('urea-and-creatinine.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('urea-and-creatinine.store'), [])
        ->assertSessionHasErrors(['urea_level', 'creatinine_level', 'report_date']);

    $this->assertDatabaseCount('urea_and_creatinines', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = UreaAndCreatinine::factory()->create(['urea_level' => 20, 'creatinine_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['urea_level', 'creatinine_level', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('urea-and-creatinine.store') : route('urea-and-creatinine.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('urea_and_creatinines', 1);
    $this->assertDatabaseHas('urea_and_creatinines', $original);
})->with([
    'negative urea_level' => ['urea_level', -1, 'The urea level field must be at least 0.'],
    'non-numeric urea_level' => ['urea_level', 'invalid', 'The urea level field must be a number.'],
    'negative creatinine_level' => ['creatinine_level', -1, 'The creatinine level field must be at least 0.'],
    'non-numeric creatinine_level' => ['creatinine_level', 'invalid', 'The creatinine level field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['urea_level' => 0, 'creatinine_level' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('urea-and-creatinine.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('urea-and-creatinine.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('urea_and_creatinines', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('urea_and_creatinines', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = UreaAndCreatinine::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['urea_level' => 20, 'creatinine_level' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('urea-and-creatinine.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('urea-and-creatinine.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('urea_and_creatinines', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('urea-and-creatinine.'.$action, 999), ['urea_level' => 20, 'creatinine_level' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = UreaAndCreatinine::factory()->create(['urea_level' => 80]);
    UreaAndCreatinine::factory()->for($record->medicalFile)->create(['urea_level' => 20]);
    UreaAndCreatinine::factory()->create(['urea_level' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('urea-and-creatinine.index', ['sort_by' => 'urea_level', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('ureaAndCreatinines.data', 1)
        ->where('ureaAndCreatinines.total', 2)
        ->where('ureaAndCreatinines.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = UreaAndCreatinine::factory()->create([$field => '2026-08-10 12:00:00']);
    UreaAndCreatinine::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    UreaAndCreatinine::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('urea-and-creatinine.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('ureaAndCreatinines.data', 1)
        ->where('ureaAndCreatinines.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = UreaAndCreatinine::factory()->create(['urea_level' => 20, 'creatinine_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.UreaAndCreatinine::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('urea-and-creatinine.store') : route('urea-and-creatinine.'.$action, $record);

        $this->from(route('urea-and-creatinine.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['urea_level', 'creatinine_level', 'report_date']), 'urea_level' => 30])
            ->assertRedirect(route('urea-and-creatinine.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('urea_and_creatinines', 1);
        $this->assertDatabaseHas('urea_and_creatinines', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = UreaAndCreatinine::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
