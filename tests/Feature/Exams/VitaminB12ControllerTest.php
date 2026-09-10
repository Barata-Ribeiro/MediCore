<?php

use App\Models\Exams\VitaminB12;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = VitaminB12::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('vitamin-b12.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = VitaminB12::factory()->create(['vitamin_b12_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    VitaminB12::factory()->for($record->medicalFile)->create(['vitamin_b12_level' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    VitaminB12::factory()->create(['vitamin_b12_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('vitamin-b12.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('vitaminB12s.data', 1)
        ->where('vitaminB12s.data.0.id', $record->id)
    );
})->with(['vitamin_b12_level']);

describe('tests for the "index" method of VitaminB12Controller', function () {
    $componentName = 'exams/vitamin-b12/index';

    it('should return empty data if no vitamin b12 record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('vitamin-b12.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.vitamin_b12_pages.index.head_title', 'Vitamin B12 Exams')
                ->has('vitaminB12s.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with vitamin b12 data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        VitaminB12::factory()->for($medicalFile)->create([
            'vitamin_b12_level' => 325,
            'report_date' => now()->toDateString(),
        ]);

        VitaminB12::factory()->for($medicalFile)->create([
            'vitamin_b12_level' => 281,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('vitamin-b12.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('vitaminB12s.data', 2)
            ->has('chartData')
            ->where('chartData.0.datasets.vitamin_b12_level.label', 'Vitamin B12')
            ->has('vitaminB12s.data.0', fn (AssertableInertia $item) => $item
                ->where('vitamin_b12_level', 325)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('vitaminB12s.data.1', fn (AssertableInertia $item) => $item
                ->where('vitamin_b12_level', 281)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->get(route('vitamin-b12.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of VitaminB12Controller', function () {
    $componentName = 'exams/vitamin-b12/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('vitamin-b12.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.vitamin_b12_pages.create.head_title', 'Create Vitamin B12 record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->get(route('vitamin-b12.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of VitaminB12Controller', function () {
    it('should store a new vitamin b12 record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'vitamin_b12_level' => 354,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('vitamin-b12.store'), $data);

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12_s', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->post(route('vitamin-b12.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of VitaminB12Controller', function () {
    $componentName = 'exams/vitamin-b12/edit';

    it('should return the edit view with vitamin b12 data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminB12 = VitaminB12::factory()->for($medicalFile)->create([
            'vitamin_b12_level' => 317,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('vitamin-b12.edit', $vitaminB12));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.vitamin_b12_pages.edit.head_title', 'Edit Vitamin B12 record')
            ->has('vitaminB12', fn (AssertableInertia $item) => $item
                ->where('vitamin_b12_level', 317)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->get(route('vitamin-b12.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of VitaminB12Controller', function () {
    it('should update the vitamin b12 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminB12 = VitaminB12::factory()->for($medicalFile)->create([
            'vitamin_b12_level' => 262,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'vitamin_b12_level' => 398,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('vitamin-b12.update', $vitaminB12), $updatedData);

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12_s', [...$updatedData, 'id' => $vitaminB12->id]);
    });

    it('should not update a vitamin b12 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminB12 = VitaminB12::factory()->for($ownerMedicalFile)->create([
            'vitamin_b12_level' => 225,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'vitamin_b12_level' => 450,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('vitamin-b12.index'))
            ->actingAs($anotherUser)
            ->put(route('vitamin-b12.update', $vitaminB12), $updatedData);

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12_s', [
            'id' => $vitaminB12->id,
            'vitamin_b12_level' => 225,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->put(route('vitamin-b12.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of VitaminB12Controller', function () {
    it('should delete the vitamin b12 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminB12 = VitaminB12::factory()->for($medicalFile)->create([
            'vitamin_b12_level' => 294,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('vitamin-b12.destroy', $vitaminB12));

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseMissing('vitamin_b12_s', ['id' => $vitaminB12->id]);
    });

    it('should not delete a vitamin b12 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminB12 = VitaminB12::factory()->for($ownerMedicalFile)->create([
            'vitamin_b12_level' => 246,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('vitamin-b12.index'))
            ->actingAs($anotherUser)
            ->delete(route('vitamin-b12.destroy', $vitaminB12));

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12_s', ['id' => $vitaminB12->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->delete(route('vitamin-b12.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('vitamin-b12.store'), [])
        ->assertSessionHasErrors(['vitamin_b12_level', 'report_date']);

    $this->assertDatabaseCount('vitamin_b12_s', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = VitaminB12::factory()->create(['vitamin_b12_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['vitamin_b12_level', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('vitamin-b12.store') : route('vitamin-b12.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('vitamin_b12_s', 1);
    $this->assertDatabaseHas('vitamin_b12_s', $original);
})->with([
    'negative vitamin_b12_level' => ['vitamin_b12_level', -1, 'The vitamin b12 level field must be at least 0.'],
    'non-numeric vitamin_b12_level' => ['vitamin_b12_level', 'invalid', 'The vitamin b12 level field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['vitamin_b12_level' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('vitamin-b12.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('vitamin-b12.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('vitamin_b12_s', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('vitamin_b12_s', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = VitaminB12::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['vitamin_b12_level' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('vitamin-b12.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('vitamin-b12.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('vitamin_b12_s', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('vitamin-b12.'.$action, 999), ['vitamin_b12_level' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = VitaminB12::factory()->create(['vitamin_b12_level' => 80]);
    VitaminB12::factory()->for($record->medicalFile)->create(['vitamin_b12_level' => 20]);
    VitaminB12::factory()->create(['vitamin_b12_level' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('vitamin-b12.index', ['sort_by' => 'vitamin_b12_level', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('vitaminB12s.data', 1)
        ->where('vitaminB12s.total', 2)
        ->where('vitaminB12s.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = VitaminB12::factory()->create([$field => '2026-08-10 12:00:00']);
    VitaminB12::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    VitaminB12::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('vitamin-b12.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('vitaminB12s.data', 1)
        ->where('vitaminB12s.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = VitaminB12::factory()->create(['vitamin_b12_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.VitaminB12::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('vitamin-b12.store') : route('vitamin-b12.'.$action, $record);

        $this->from(route('vitamin-b12.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['vitamin_b12_level', 'report_date']), 'vitamin_b12_level' => 30])
            ->assertRedirect(route('vitamin-b12.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('vitamin_b12_s', 1);
        $this->assertDatabaseHas('vitamin_b12_s', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = VitaminB12::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
