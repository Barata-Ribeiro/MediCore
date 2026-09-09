<?php

use App\Models\Exams\LipidProfile;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = LipidProfile::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('lipid-profile.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = LipidProfile::factory()->create(['total_cholesterol' => 12, 'hdl_cholesterol' => 12, 'ldl_cholesterol' => 12, 'vldl_cholesterol' => 12, 'triglycerides' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    LipidProfile::factory()->for($record->medicalFile)->create(['total_cholesterol' => 12, 'hdl_cholesterol' => 12, 'ldl_cholesterol' => 12, 'vldl_cholesterol' => 12, 'triglycerides' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    LipidProfile::factory()->create(['total_cholesterol' => 12, 'hdl_cholesterol' => 12, 'ldl_cholesterol' => 12, 'vldl_cholesterol' => 12, 'triglycerides' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('lipid-profile.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('lipidProfiles.data', 1)
        ->where('lipidProfiles.data.0.id', $record->id)
    );
})->with(['total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides']);

describe('tests for the "index" method of LipidProfileController', function () {
    $componentName = 'exams/lipid-profile/index';

    it('should return empty data if no lipid profile exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('lipid-profile.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->has('lipidProfiles.data', 0)
        );
    });

    it('should return successful response with lipid profile data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        LipidProfile::factory()->for($medicalFile)->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        LipidProfile::factory()->for($medicalFile)->create([
            'total_cholesterol' => 120,
            'hdl_cholesterol' => 60,
            'ldl_cholesterol' => 30,
            'vldl_cholesterol' => 15,
            'triglycerides' => 90,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('lipid-profile.index'));

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('lipidProfiles.data', 2)
            ->has('lipidProfiles.data.0', fn (AssertableInertia $item) => $item
                ->where('total_cholesterol', 100)
                ->where('hdl_cholesterol', 50)
                ->where('ldl_cholesterol', 20)
                ->where('vldl_cholesterol', 10)
                ->where('triglycerides', 80)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('lipidProfiles.data.1', fn (AssertableInertia $item) => $item
                ->where('total_cholesterol', 120)
                ->where('hdl_cholesterol', 60)
                ->where('ldl_cholesterol', 30)
                ->where('vldl_cholesterol', 15)
                ->where('triglycerides', 90)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should return localized chart labels for portuguese users', function () use ($componentName) {
        $user = User::factory()->create(['locale' => 'pt_BR']);
        $medicalFile = $user->medicalFile()->create();

        LipidProfile::factory()->for($medicalFile)->create([
            'total_cholesterol' => 180,
            'hdl_cholesterol' => 55,
            'ldl_cholesterol' => 95,
            'vldl_cholesterol' => 30,
            'triglycerides' => 150,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->withHeader('Accept-Language', '')
            ->get(route('lipid-profile.index'));

        $response->assertOk();
        $response->assertSee('lang="pt-BR"', false);
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('auth.locale', 'pt_BR')
            ->where('chartData.0.datasets.total_cholesterol.label', 'Colesterol Total')
            ->where('chartData.0.datasets.hdl_cholesterol.label', 'Colesterol HDL')
            ->where('chartData.0.datasets.ldl_cholesterol.label', 'Colesterol LDL')
            ->where('chartData.0.datasets.vldl_cholesterol.label', 'Colesterol VLDL')
            ->where('chartData.0.datasets.triglycerides.label', 'Triglicerídeos')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('lipid-profile.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of LipidProfileController', function () {
    $componentName = 'exams/lipid-profile/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('lipid-profile.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName));
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('lipid-profile.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of LipidProfileController', function () {
    it('should store a new lipid profile record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('lipid-profile.store'), $data);

        $response->assertRedirect(route('lipid-profile.index'));
        $this->assertDatabaseHas('lipid_profiles', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('lipid-profile.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of LipidProfileController', function () {
    $componentName = 'exams/lipid-profile/edit';

    it('should return the edit view with lipid profile data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $lipidProfile = LipidProfile::factory()->for($medicalFile)->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('lipid-profile.edit', $lipidProfile));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('lipidProfile', fn (AssertableInertia $item) => $item
                ->where('total_cholesterol', 100)
                ->where('hdl_cholesterol', 50)
                ->where('ldl_cholesterol', 20)
                ->where('vldl_cholesterol', 10)
                ->where('triglycerides', 80)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('lipid-profile.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of LipidProfileController', function () {
    it('should update the lipid profile record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $lipidProfile = LipidProfile::factory()->for($medicalFile)->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'total_cholesterol' => 120,
            'hdl_cholesterol' => 60,
            'ldl_cholesterol' => 30,
            'vldl_cholesterol' => 15,
            'triglycerides' => 90,
            'report_date' => now()->subDays(30)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('lipid-profile.update', $lipidProfile), $updatedData);

        $response->assertRedirect(route('lipid-profile.index'));
        $this->assertDatabaseHas('lipid_profiles', [...$updatedData, 'id' => $lipidProfile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('lipid-profile.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of LipidProfileController', function () {
    it('should delete the lipid profile record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $lipidProfile = LipidProfile::factory()->for($medicalFile)->create([
            'total_cholesterol' => 100,
            'hdl_cholesterol' => 50,
            'ldl_cholesterol' => 20,
            'vldl_cholesterol' => 10,
            'triglycerides' => 80,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('lipid-profile.destroy', $lipidProfile));

        $response->assertRedirect(route('lipid-profile.index'));
        $this->assertDatabaseMissing('lipid_profiles', ['id' => $lipidProfile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('lipid-profile.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('lipid-profile.store'), [])
        ->assertSessionHasErrors(['total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides', 'report_date']);

    $this->assertDatabaseCount('lipid_profiles', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = LipidProfile::factory()->create(['total_cholesterol' => 20, 'hdl_cholesterol' => 20, 'ldl_cholesterol' => 20, 'vldl_cholesterol' => 20, 'triglycerides' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('lipid-profile.store') : route('lipid-profile.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('lipid_profiles', 1);
    $this->assertDatabaseHas('lipid_profiles', $original);
})->with([
    'negative total_cholesterol' => ['total_cholesterol', -1, 'The total cholesterol field must be at least 0.'],
    'non-numeric total_cholesterol' => ['total_cholesterol', 'invalid', 'The total cholesterol field must be a number.'],
    'negative hdl_cholesterol' => ['hdl_cholesterol', -1, 'The hdl cholesterol field must be at least 0.'],
    'non-numeric hdl_cholesterol' => ['hdl_cholesterol', 'invalid', 'The hdl cholesterol field must be a number.'],
    'negative ldl_cholesterol' => ['ldl_cholesterol', -1, 'The ldl cholesterol field must be at least 0.'],
    'non-numeric ldl_cholesterol' => ['ldl_cholesterol', 'invalid', 'The ldl cholesterol field must be a number.'],
    'negative vldl_cholesterol' => ['vldl_cholesterol', -1, 'The vldl cholesterol field must be at least 0.'],
    'non-numeric vldl_cholesterol' => ['vldl_cholesterol', 'invalid', 'The vldl cholesterol field must be a number.'],
    'negative triglycerides' => ['triglycerides', -1, 'The triglycerides field must be at least 0.'],
    'non-numeric triglycerides' => ['triglycerides', 'invalid', 'The triglycerides field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['total_cholesterol' => 0, 'hdl_cholesterol' => 0, 'ldl_cholesterol' => 0, 'vldl_cholesterol' => 0, 'triglycerides' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('lipid-profile.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('lipid-profile.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('lipid_profiles', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('lipid_profiles', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = LipidProfile::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['total_cholesterol' => 20, 'hdl_cholesterol' => 20, 'ldl_cholesterol' => 20, 'vldl_cholesterol' => 20, 'triglycerides' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('lipid-profile.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('lipid-profile.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('lipid_profiles', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('lipid-profile.'.$action, 999), ['total_cholesterol' => 20, 'hdl_cholesterol' => 20, 'ldl_cholesterol' => 20, 'vldl_cholesterol' => 20, 'triglycerides' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = LipidProfile::factory()->create(['total_cholesterol' => 80]);
    LipidProfile::factory()->for($record->medicalFile)->create(['total_cholesterol' => 20]);
    LipidProfile::factory()->create(['total_cholesterol' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('lipid-profile.index', ['sort_by' => 'total_cholesterol', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('lipidProfiles.data', 1)
        ->where('lipidProfiles.total', 2)
        ->where('lipidProfiles.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = LipidProfile::factory()->create([$field => '2026-08-10 12:00:00']);
    LipidProfile::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    LipidProfile::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('lipid-profile.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('lipidProfiles.data', 1)
        ->where('lipidProfiles.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = LipidProfile::factory()->create(['total_cholesterol' => 20, 'hdl_cholesterol' => 20, 'ldl_cholesterol' => 20, 'vldl_cholesterol' => 20, 'triglycerides' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.LipidProfile::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('lipid-profile.store') : route('lipid-profile.'.$action, $record);

        $this->from(route('lipid-profile.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides', 'report_date']), 'total_cholesterol' => 30])
            ->assertRedirect(route('lipid-profile.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('lipid_profiles', 1);
        $this->assertDatabaseHas('lipid_profiles', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = LipidProfile::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
