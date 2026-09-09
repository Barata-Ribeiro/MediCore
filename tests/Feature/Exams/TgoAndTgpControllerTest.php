<?php

use App\Models\Exams\TgoAndTgp;
use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Seeders\Exams\TgoAndTgpSeeder;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

it('does not expose another user record on the edit page', function () {
    $record = TgoAndTgp::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('tgo-and-tgp.edit', $record))
        ->assertNotFound();
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('tgo-and-tgp.'.$action, 999), ['tgo_level' => 30, 'tgp_level' => 25, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    ['get', 'edit'],
    ['put', 'update'],
    ['delete', 'destroy'],
]);

it('rejects missing fields', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('tgo-and-tgp.store'), [])
        ->assertSessionHasErrors(['tgo_level', 'tgp_level', 'report_date']);

    $this->assertDatabaseCount('tgo_and_tgps', 0);
});

it('rejects invalid results on creation and update', function (string $field, mixed $value, string $message, string $method) {
    $record = TgoAndTgp::factory()->create(['tgo_level' => 30, 'tgp_level' => 25, 'report_date' => '2026-08-10']);
    $data = ['tgo_level' => 30, 'tgp_level' => 25, 'report_date' => '2026-08-10', $field => $value];
    $route = $method === 'post' ? route('tgo-and-tgp.store') : route('tgo-and-tgp.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('tgo_and_tgps', 1);
    $this->assertDatabaseHas('tgo_and_tgps', ['id' => $record->id, 'tgo_level' => 30, 'tgp_level' => 25, 'report_date' => '2026-08-10']);
})->with([
    'negative TGO' => ['tgo_level', -1, 'The TGO (AST) field must be at least 0.'],
    'negative TGP' => ['tgp_level', -1, 'The TGP (ALT) field must be at least 0.'],
    'non-numeric TGO' => ['tgo_level', 'invalid', 'The TGO (AST) field must be a number.'],
    'non-numeric TGP' => ['tgp_level', 'invalid', 'The TGP (ALT) field must be a number.'],
    'invalid date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();

    $this->actingAs($user)->post(route('tgo-and-tgp.store'), [
        'tgo_level' => 0,
        'tgp_level' => 0,
        'report_date' => '2026-08-10',
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('tgo-and-tgp.index'))
        ->assertInertiaFlash('toast.message', 'TGO and TGP record created successfully.');

    $this->assertDatabaseHas('tgo_and_tgps', ['medical_file_id' => $medicalFile->id, 'tgo_level' => 0, 'tgp_level' => 0]);
    $this->assertDatabaseMissing('tgo_and_tgps', ['medical_file_id' => $otherMedicalFile->id]);
});

it('searches both measurements without exposing other users or bypassing date filters', function (string $field) {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $match = TgoAndTgp::factory()->for($medicalFile)->create(['tgo_level' => 12, 'tgp_level' => 13, $field => 87.5, 'report_date' => '2026-08-10']);
    TgoAndTgp::factory()->for($medicalFile)->create(['tgo_level' => 87.5, 'tgp_level' => 87.5, 'report_date' => '2026-07-10']);
    TgoAndTgp::factory()->create(['tgo_level' => 87.5, 'tgp_level' => 87.5, 'report_date' => '2026-08-10']);

    $this->actingAs($user)->get(route('tgo-and-tgp.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [CarbonImmutable::parse('2026-08-10')->getTimestampMs(), CarbonImmutable::parse('2026-08-10')->getTimestampMs()]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('tgoAndTgps.data', 1)
        ->where('tgoAndTgps.data.0.id', $match->id)
        ->has('chartData', 2)
        ->where('chartData.1.datasets.'.$field.'.data', 87.5)
    );
})->with(['tgo_level', 'tgp_level']);

it('shows daily averages for the five most recent dates in chronological order', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    foreach (range(1, 6) as $day) {
        TgoAndTgp::factory()->for($medicalFile)->create(['tgo_level' => 20, 'tgp_level' => 30, 'report_date' => '2026-08-0'.$day]);
    }
    TgoAndTgp::factory()->for($medicalFile)->create(['tgo_level' => 40, 'tgp_level' => 50, 'report_date' => '2026-08-06']);
    TgoAndTgp::factory()->create(['tgo_level' => 900, 'tgp_level' => 900, 'report_date' => '2026-08-06']);

    $this->actingAs($user)->get(route('tgo-and-tgp.index'))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('tgoAndTgps.data', 7)
        ->has('chartData', 5)
        ->where('chartData.0.x_axis_label', '2026-08-02')
        ->where('chartData.4.x_axis_label', '2026-08-06')
        ->where('chartData.4.datasets.tgo_level.data', 30)
        ->where('chartData.4.datasets.tgp_level.data', 40)
        );
});

it('sorts and paginates results', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    TgoAndTgp::factory()->for($medicalFile)->create(['tgo_level' => 20]);
    $highest = TgoAndTgp::factory()->for($medicalFile)->create(['tgo_level' => 80]);

    $this->actingAs($user)->get(route('tgo-and-tgp.index', ['sort_by' => 'tgo_level', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('tgoAndTgps.data', 1)
        ->where('tgoAndTgps.total', 2)
        ->where('tgoAndTgps.data.0.id', $highest->id)
        );
});

it('falls back to id for unsupported sort columns', function () {
    $record = TgoAndTgp::factory()->create();

    $this->actingAs($record->medicalFile->user)->get(route('tgo-and-tgp.index', ['sort_by' => 'password']))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page->where('tgoAndTgps.data.0.id', $record->id));
});

it('loads the Portuguese page and validation translations', function () {
    $user = User::factory()->create(['locale' => 'pt_BR']);
    $user->medicalFile()->create();

    $this->actingAs($user)->get(route('tgo-and-tgp.create'))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->where('lang.tgo_and_tgp_pages.create.head_title', 'Criar registro de TGO e TGP')
        ->where('lang.tgo_and_tgp_pages.form.tgo_level', 'Aspartato Aminotransferase - TGO')
        ->where('lang.tgo_and_tgp_pages.form.tgp_level', 'Alanina Aminotransferase - TGP')
        ->where('lang.tgo_and_tgp_pages.shared.unit', 'U/L')
        );

    $this->post(route('tgo-and-tgp.store'), ['tgo_level' => -1, 'tgp_level' => 25, 'report_date' => '2026-08-10'])
        ->assertSessionHasErrors(['tgo_level' => 'O campo TGO (AST) deve ser pelo menos 0.']);

    $this->post(route('tgo-and-tgp.store'), ['tgo_level' => 30, 'tgp_level' => 25, 'report_date' => '2026-08-10'])
        ->assertRedirect(route('tgo-and-tgp.index'))
        ->assertInertiaFlash('toast.message', 'Registro de TGO e TGP criado com sucesso.');
});

it('keeps the record owner when updating values', function () {
    $record = TgoAndTgp::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();

    $this->actingAs($record->medicalFile->user)->put(route('tgo-and-tgp.update', $record), [
        'tgo_level' => 31.25,
        'tgp_level' => 27.75,
        'report_date' => '2026-08-10',
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('tgo-and-tgp.index'))
        ->assertInertiaFlash('toast.message', 'TGO and TGP record updated successfully.');

    $this->assertDatabaseHas('tgo_and_tgps', ['id' => $record->id, 'medical_file_id' => $record->medical_file_id, 'tgo_level' => 31.25, 'tgp_level' => 27.75]);
});

it('returns an error toast and preserves data when persistence fails', function (string $event, string $method, string $action) {
    $record = TgoAndTgp::factory()->create(['tgo_level' => 30, 'tgp_level' => 25, 'report_date' => '2026-08-10']);
    $eventName = 'eloquent.'.$event.': '.TgoAndTgp::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('tgo-and-tgp.store') : route('tgo-and-tgp.'.$action, $record);
        $this->from(route('tgo-and-tgp.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, ['tgo_level' => 50, 'tgp_level' => 45, 'report_date' => '2026-08-11'])
            ->assertRedirect(route('tgo-and-tgp.index'))
            ->assertInertiaFlash('toast.type', 'error')
            ->assertInertiaFlash('toast.message', __('flash.exams.tgo_and_tgp.'.$action.'_failed'));

        $this->assertDatabaseCount('tgo_and_tgps', 1);
        $this->assertDatabaseHas('tgo_and_tgps', ['id' => $record->id, 'tgo_level' => 30, 'tgp_level' => 25, 'report_date' => '2026-08-10']);
    } finally {
        Event::forget($eventName);
    }
})->with([
    ['creating', 'post', 'store'],
    ['updating', 'put', 'update'],
    ['deleting', 'delete', 'destroy'],
]);

it('filters records by their creation date', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $record = TgoAndTgp::factory()->for($medicalFile)->create(['created_at' => '2026-08-10 12:00:00']);
    TgoAndTgp::factory()->for($medicalFile)->create(['created_at' => '2026-08-09 12:00:00']);

    $this->actingAs($user)->get(route('tgo-and-tgp.index', [
        'filters' => ['created_at' => [CarbonImmutable::parse('2026-08-10')->getTimestampMs(), CarbonImmutable::parse('2026-08-10')->getTimestampMs()]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('tgoAndTgps.data', 1)
        ->where('tgoAndTgps.data.0.id', $record->id)
    );
});

it('removes the paired results when the medical file is deleted', function () {
    $record = TgoAndTgp::factory()->create();
    $record->medicalFile->delete();

    $this->assertDatabaseMissing('tgo_and_tgps', ['id' => $record->id]);
});

it('seeds sample results for a separate medical file', function () {
    $this->seed(TgoAndTgpSeeder::class);

    $this->assertDatabaseCount('tgo_and_tgps', 5);
    expect(TgoAndTgp::query()->distinct()->pluck('medical_file_id'))->toHaveCount(1);
});

describe('tests for the "index" method of TgoAndTgpController', function () {
    $componentName = 'exams/tgo-and-tgp/index';

    it('should return empty data if no TGO and TGP record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('tgo-and-tgp.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.tgo_and_tgp_pages.index.head_title', 'TGO and TGP Exams')
                ->has('tgoAndTgps.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with TGO and TGP data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        TgoAndTgp::factory()->for($medicalFile)->create([
            'tgo_level' => 32.5,
            'tgp_level' => 1.1,
            'report_date' => now()->toDateString(),
        ]);

        TgoAndTgp::factory()->for($medicalFile)->create([
            'tgo_level' => 28.4,
            'tgp_level' => 0.9,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('tgo-and-tgp.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.tgo_and_tgp_pages.index.head_title', 'TGO and TGP Exams')
            ->has('tgoAndTgps.data', 2)
            ->has('chartData')
            ->where('chartData.0.datasets.tgo_level.label', 'Aspartate Aminotransferase - TGO (AST)')
            ->where('chartData.0.datasets.tgp_level.label', 'Alanine Aminotransferase - TGP (ALT)')
            ->has('tgoAndTgps.data.0', fn (AssertableInertia $item) => $item
                ->where('tgo_level', 32.5)
                ->where('tgp_level', 1.1)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('tgoAndTgps.data.1', fn (AssertableInertia $item) => $item
                ->where('tgo_level', 28.4)
                ->where('tgp_level', 0.9)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('tgo-and-tgp.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of TgoAndTgpController', function () {
    $componentName = 'exams/tgo-and-tgp/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('tgo-and-tgp.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.tgo_and_tgp_pages.create.head_title', 'Create TGO and TGP record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('tgo-and-tgp.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of TgoAndTgpController', function () {
    it('should store a new TGO and TGP record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'tgo_level' => 34.8,
            'tgp_level' => 1.2,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('tgo-and-tgp.store'), $data);

        $response->assertRedirect(route('tgo-and-tgp.index'));
        $this->assertDatabaseHas('tgo_and_tgps', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('tgo-and-tgp.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of TgoAndTgpController', function () {
    $componentName = 'exams/tgo-and-tgp/edit';

    it('should return the edit view with TGO and TGP data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $tgoAndTgp = TgoAndTgp::factory()->for($medicalFile)->create([
            'tgo_level' => 30.7,
            'tgp_level' => 1.0,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('tgo-and-tgp.edit', $tgoAndTgp));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.tgo_and_tgp_pages.edit.head_title', 'Edit TGO and TGP record')
            ->has('tgoAndTgp', fn (AssertableInertia $item) => $item
                ->where('tgo_level', 30.7)
                ->where('tgp_level', 1)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('tgo-and-tgp.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of TgoAndTgpController', function () {
    it('should update the TGO and TGP record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $tgoAndTgp = TgoAndTgp::factory()->for($medicalFile)->create([
            'tgo_level' => 27.3,
            'tgp_level' => 0.8,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'tgo_level' => 38.1,
            'tgp_level' => 1.3,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('tgo-and-tgp.update', $tgoAndTgp), $updatedData);

        $response->assertRedirect(route('tgo-and-tgp.index'));
        $this->assertDatabaseHas('tgo_and_tgps', [...$updatedData, 'id' => $tgoAndTgp->id]);
    });

    it('should not update a TGO and TGP record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $tgoAndTgp = TgoAndTgp::factory()->for($ownerMedicalFile)->create([
            'tgo_level' => 25.6,
            'tgp_level' => 0.7,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'tgo_level' => 41.0,
            'tgp_level' => 1.4,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('tgo-and-tgp.index'))
            ->actingAs($anotherUser)
            ->put(route('tgo-and-tgp.update', $tgoAndTgp), $updatedData);

        $response->assertRedirect(route('tgo-and-tgp.index'));
        $this->assertDatabaseHas('tgo_and_tgps', [
            'id' => $tgoAndTgp->id,
            'tgo_level' => 25.6,
            'tgp_level' => 0.7,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('tgo-and-tgp.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of TgoAndTgpController', function () {
    it('should delete the TGO and TGP record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $tgoAndTgp = TgoAndTgp::factory()->for($medicalFile)->create([
            'tgo_level' => 29.9,
            'tgp_level' => 1.1,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('tgo-and-tgp.destroy', $tgoAndTgp));

        $response->assertRedirect(route('tgo-and-tgp.index'));
        $this->assertDatabaseMissing('tgo_and_tgps', ['id' => $tgoAndTgp->id]);
    });

    it('should not delete a TGO and TGP record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $tgoAndTgp = TgoAndTgp::factory()->for($ownerMedicalFile)->create([
            'tgo_level' => 24.2,
            'tgp_level' => 0.9,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('tgo-and-tgp.index'))
            ->actingAs($anotherUser)
            ->delete(route('tgo-and-tgp.destroy', $tgoAndTgp));

        $response->assertRedirect(route('tgo-and-tgp.index'));
        $this->assertDatabaseHas('tgo_and_tgps', ['id' => $tgoAndTgp->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('tgo-and-tgp.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
