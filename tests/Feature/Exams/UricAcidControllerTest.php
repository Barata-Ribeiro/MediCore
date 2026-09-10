<?php

use App\Models\Exams\UricAcid;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = UricAcid::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('uric-acid.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = UricAcid::factory()->create(['uric_acid_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    UricAcid::factory()->for($record->medicalFile)->create(['uric_acid_level' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    UricAcid::factory()->create(['uric_acid_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('uric-acid.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('uricAcids.data', 1)
        ->where('uricAcids.data.0.id', $record->id)
    );
})->with(['uric_acid_level']);

describe('tests for the "index" method of UricAcidController', function () {
    $componentName = 'exams/uric-acid/index';

    it('should return empty data if no uric acid record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('uric-acid.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.uric_acid_pages.index.head_title', 'Uric Acid Exams')
                ->has('uricAcids.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with uric acid data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        UricAcid::factory()->for($medicalFile)->create([
            'uric_acid_level' => 6.8,
            'report_date' => now()->toDateString(),
        ]);

        UricAcid::factory()->for($medicalFile)->create([
            'uric_acid_level' => 5.9,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('uric-acid.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.uric_acid_pages.index.head_title', 'Uric Acid Exams')
            ->has('uricAcids.data', 2)
            ->has('chartData')
            ->has('uricAcids.data.0', fn (AssertableInertia $item) => $item
                ->where('uric_acid_level', 6.8)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('uricAcids.data.1', fn (AssertableInertia $item) => $item
                ->where('uric_acid_level', 5.9)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('uric-acid.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of UricAcidController', function () {
    $componentName = 'exams/uric-acid/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('uric-acid.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.uric_acid_pages.create.head_title', 'Create Uric Acid record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('uric-acid.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of UricAcidController', function () {
    it('should store a new uric acid record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'uric_acid_level' => 7.1,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('uric-acid.store'), $data);

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('uric-acid.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of UricAcidController', function () {
    $componentName = 'exams/uric-acid/edit';

    it('should return the edit view with uric acid data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $uricAcid = UricAcid::factory()->for($medicalFile)->create([
            'uric_acid_level' => 6.4,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('uric-acid.edit', $uricAcid));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.uric_acid_pages.edit.head_title', 'Edit Uric Acid record')
            ->has('uricAcid', fn (AssertableInertia $item) => $item
                ->where('uric_acid_level', 6.4)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('uric-acid.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of UricAcidController', function () {
    it('should update the uric acid record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $uricAcid = UricAcid::factory()->for($medicalFile)->create([
            'uric_acid_level' => 5.8,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'uric_acid_level' => 7.4,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('uric-acid.update', $uricAcid), $updatedData);

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', [...$updatedData, 'id' => $uricAcid->id]);
    });

    it('should not update a uric acid record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $uricAcid = UricAcid::factory()->for($ownerMedicalFile)->create([
            'uric_acid_level' => 6.2,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'uric_acid_level' => 8.1,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('uric-acid.index'))
            ->actingAs($anotherUser)
            ->put(route('uric-acid.update', $uricAcid), $updatedData);

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', [
            'id' => $uricAcid->id,
            'uric_acid_level' => 6.2,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('uric-acid.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of UricAcidController', function () {
    it('should delete the uric acid record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $uricAcid = UricAcid::factory()->for($medicalFile)->create([
            'uric_acid_level' => 6.7,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('uric-acid.destroy', $uricAcid));

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseMissing('uric_acids', ['id' => $uricAcid->id]);
    });

    it('should not delete a uric acid record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $uricAcid = UricAcid::factory()->for($ownerMedicalFile)->create([
            'uric_acid_level' => 5.6,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('uric-acid.index'))
            ->actingAs($anotherUser)
            ->delete(route('uric-acid.destroy', $uricAcid));

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', ['id' => $uricAcid->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('uric-acid.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('uric-acid.store'), [])
        ->assertSessionHasErrors(['uric_acid_level', 'report_date']);

    $this->assertDatabaseCount('uric_acids', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = UricAcid::factory()->create(['uric_acid_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['uric_acid_level', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('uric-acid.store') : route('uric-acid.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('uric_acids', 1);
    $this->assertDatabaseHas('uric_acids', $original);
})->with([
    'negative uric_acid_level' => ['uric_acid_level', -1, 'The uric acid level field must be at least 0.'],
    'non-numeric uric_acid_level' => ['uric_acid_level', 'invalid', 'The uric acid level field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['uric_acid_level' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('uric-acid.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('uric-acid.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('uric_acids', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('uric_acids', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = UricAcid::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['uric_acid_level' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('uric-acid.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('uric-acid.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('uric_acids', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('uric-acid.'.$action, 999), ['uric_acid_level' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = UricAcid::factory()->create(['uric_acid_level' => 80]);
    UricAcid::factory()->for($record->medicalFile)->create(['uric_acid_level' => 20]);
    UricAcid::factory()->create(['uric_acid_level' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('uric-acid.index', ['sort_by' => 'uric_acid_level', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('uricAcids.data', 1)
        ->where('uricAcids.total', 2)
        ->where('uricAcids.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = UricAcid::factory()->create([$field => '2026-08-10 12:00:00']);
    UricAcid::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    UricAcid::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('uric-acid.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('uricAcids.data', 1)
        ->where('uricAcids.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = UricAcid::factory()->create(['uric_acid_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.UricAcid::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('uric-acid.store') : route('uric-acid.'.$action, $record);

        $this->from(route('uric-acid.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['uric_acid_level', 'report_date']), 'uric_acid_level' => 30])
            ->assertRedirect(route('uric-acid.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('uric_acids', 1);
        $this->assertDatabaseHas('uric_acids', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = UricAcid::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
