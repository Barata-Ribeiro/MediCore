<?php

use App\Models\Exams\UltrasensitiveTsh;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = UltrasensitiveTsh::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('ultrasensitive-tsh.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = UltrasensitiveTsh::factory()->create(['tsh_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    UltrasensitiveTsh::factory()->for($record->medicalFile)->create(['tsh_level' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    UltrasensitiveTsh::factory()->create(['tsh_level' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('ultrasensitive-tsh.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('ultrasensitiveTshs.data', 1)
        ->where('ultrasensitiveTshs.data.0.id', $record->id)
    );
})->with(['tsh_level']);

describe('tests for the "index" method of UltrasensitiveTshController', function () {
    $componentName = 'exams/ultrasensitive-tsh/index';

    it('should return empty data if no ultrasensitive tsh record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.ultrasensitive_tsh_pages.index.head_title', 'Ultrasensitive TSH Exams')
                ->has('ultrasensitiveTshs.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with ultrasensitive tsh data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        UltrasensitiveTsh::factory()->for($medicalFile)->create([
            'tsh_level' => 2.35,
            'report_date' => now()->toDateString(),
        ]);

        UltrasensitiveTsh::factory()->for($medicalFile)->create([
            'tsh_level' => 1.82,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('ultrasensitiveTshs.data', 2)
            ->has('chartData')
            ->has('ultrasensitiveTshs.data.0', fn (AssertableInertia $item) => $item
                ->where('tsh_level', 2.35)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('ultrasensitiveTshs.data.1', fn (AssertableInertia $item) => $item
                ->where('tsh_level', 1.82)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('ultrasensitive-tsh.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of UltrasensitiveTshController', function () {
    $componentName = 'exams/ultrasensitive-tsh/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.ultrasensitive_tsh_pages.create.head_title', 'Create Ultrasensitive TSH record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('ultrasensitive-tsh.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of UltrasensitiveTshController', function () {
    it('should store a new ultrasensitive tsh record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'tsh_level' => 2.64,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('ultrasensitive-tsh.store'), $data);

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('ultrasensitive-tsh.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of UltrasensitiveTshController', function () {
    $componentName = 'exams/ultrasensitive-tsh/edit';

    it('should return the edit view with ultrasensitive tsh data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ultrasensitiveTsh = UltrasensitiveTsh::factory()->for($medicalFile)->create([
            'tsh_level' => 3.17,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.edit', $ultrasensitiveTsh));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.ultrasensitive_tsh_pages.edit.head_title', 'Edit Ultrasensitive TSH record')
            ->has('ultrasensitiveTsh', fn (AssertableInertia $item) => $item
                ->where('tsh_level', 3.17)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('ultrasensitive-tsh.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of UltrasensitiveTshController', function () {
    it('should update the ultrasensitive tsh record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ultrasensitiveTsh = UltrasensitiveTsh::factory()->for($medicalFile)->create([
            'tsh_level' => 1.56,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'tsh_level' => 2.91,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('ultrasensitive-tsh.update', $ultrasensitiveTsh), $updatedData);

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', [...$updatedData, 'id' => $ultrasensitiveTsh->id]);
    });

    it('should not update an ultrasensitive tsh record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ultrasensitiveTsh = UltrasensitiveTsh::factory()->for($ownerMedicalFile)->create([
            'tsh_level' => 1.24,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'tsh_level' => 3.05,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('ultrasensitive-tsh.index'))
            ->actingAs($anotherUser)
            ->put(route('ultrasensitive-tsh.update', $ultrasensitiveTsh), $updatedData);

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', [
            'id' => $ultrasensitiveTsh->id,
            'tsh_level' => 1.24,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('ultrasensitive-tsh.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of UltrasensitiveTshController', function () {
    it('should delete the ultrasensitive tsh record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ultrasensitiveTsh = UltrasensitiveTsh::factory()->for($medicalFile)->create([
            'tsh_level' => 2.48,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('ultrasensitive-tsh.destroy', $ultrasensitiveTsh));

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseMissing('ultrasensitive_tshs', ['id' => $ultrasensitiveTsh->id]);
    });

    it('should not delete an ultrasensitive tsh record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ultrasensitiveTsh = UltrasensitiveTsh::factory()->for($ownerMedicalFile)->create([
            'tsh_level' => 1.73,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('ultrasensitive-tsh.index'))
            ->actingAs($anotherUser)
            ->delete(route('ultrasensitive-tsh.destroy', $ultrasensitiveTsh));

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', ['id' => $ultrasensitiveTsh->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('ultrasensitive-tsh.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('ultrasensitive-tsh.store'), [])
        ->assertSessionHasErrors(['tsh_level', 'report_date']);

    $this->assertDatabaseCount('ultrasensitive_tshs', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = UltrasensitiveTsh::factory()->create(['tsh_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['tsh_level', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('ultrasensitive-tsh.store') : route('ultrasensitive-tsh.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('ultrasensitive_tshs', 1);
    $this->assertDatabaseHas('ultrasensitive_tshs', $original);
})->with([
    'negative tsh_level' => ['tsh_level', -1, 'The tsh level field must be at least 0.'],
    'non-numeric tsh_level' => ['tsh_level', 'invalid', 'The tsh level field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['tsh_level' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('ultrasensitive-tsh.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('ultrasensitive-tsh.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('ultrasensitive_tshs', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('ultrasensitive_tshs', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = UltrasensitiveTsh::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['tsh_level' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('ultrasensitive-tsh.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('ultrasensitive-tsh.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('ultrasensitive_tshs', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('ultrasensitive-tsh.'.$action, 999), ['tsh_level' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = UltrasensitiveTsh::factory()->create(['tsh_level' => 80]);
    UltrasensitiveTsh::factory()->for($record->medicalFile)->create(['tsh_level' => 20]);
    UltrasensitiveTsh::factory()->create(['tsh_level' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('ultrasensitive-tsh.index', ['sort_by' => 'tsh_level', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('ultrasensitiveTshs.data', 1)
        ->where('ultrasensitiveTshs.total', 2)
        ->where('ultrasensitiveTshs.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = UltrasensitiveTsh::factory()->create([$field => '2026-08-10 12:00:00']);
    UltrasensitiveTsh::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    UltrasensitiveTsh::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('ultrasensitive-tsh.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('ultrasensitiveTshs.data', 1)
        ->where('ultrasensitiveTshs.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = UltrasensitiveTsh::factory()->create(['tsh_level' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.UltrasensitiveTsh::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('ultrasensitive-tsh.store') : route('ultrasensitive-tsh.'.$action, $record);

        $this->from(route('ultrasensitive-tsh.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['tsh_level', 'report_date']), 'tsh_level' => 30])
            ->assertRedirect(route('ultrasensitive-tsh.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('ultrasensitive_tshs', 1);
        $this->assertDatabaseHas('ultrasensitive_tshs', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = UltrasensitiveTsh::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
