<?php

use App\Models\Exams\VitaminD3;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->travelTo('2026-08-10 12:00:00');
    $this->withoutVite();
});

it('does not expose another users record on the edit page', function () {
    $record = VitaminD3::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('vitamin-d3.edit', $record))
        ->assertNotFound();
});

it('searches measurements without exposing other users or bypassing date filters', function (string $field) {
    $record = VitaminD3::factory()->create(['twenty_five_hydroxyvitamin_d3' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    VitaminD3::factory()->for($record->medicalFile)->create(['twenty_five_hydroxyvitamin_d3' => 12, $field => 87.5, 'report_date' => '2026-07-10']);
    VitaminD3::factory()->create(['twenty_five_hydroxyvitamin_d3' => 12, $field => 87.5, 'report_date' => '2026-08-10']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('vitamin-d3.index', [
        'search' => '87.5',
        'filters' => ['report_date' => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('vitaminD3s.data', 1)
        ->where('vitaminD3s.data.0.id', $record->id)
    );
})->with(['twenty_five_hydroxyvitamin_d3']);

describe('tests for the "index" method of VitaminD3Controller', function () {
    $componentName = 'exams/vitamin-d3/index';

    it('should return empty data if no vitamin d3 record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('vitamin-d3.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.vitamin_d3_pages.index.head_title', 'Vitamin D3 Exams')
                ->has('vitaminD3s.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with vitamin d3 data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        VitaminD3::factory()->for($medicalFile)->create([
            'twenty_five_hydroxyvitamin_d3' => 32.5,
            'report_date' => now()->toDateString(),
        ]);

        VitaminD3::factory()->for($medicalFile)->create([
            'twenty_five_hydroxyvitamin_d3' => 28.1,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('vitamin-d3.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('vitaminD3s.data', 2)
            ->has('chartData')
            ->has('vitaminD3s.data.0', fn (AssertableInertia $item) => $item
                ->where('twenty_five_hydroxyvitamin_d3', 32.5)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('vitaminD3s.data.1', fn (AssertableInertia $item) => $item
                ->where('twenty_five_hydroxyvitamin_d3', 28.1)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('vitamin-d3.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of VitaminD3Controller', function () {
    $componentName = 'exams/vitamin-d3/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('vitamin-d3.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.vitamin_d3_pages.create.head_title', 'Create Vitamin D3 record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('vitamin-d3.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of VitaminD3Controller', function () {
    it('should store a new vitamin d3 record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'twenty_five_hydroxyvitamin_d3' => 35.4,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('vitamin-d3.store'), $data);

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('vitamin-d3.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of VitaminD3Controller', function () {
    $componentName = 'exams/vitamin-d3/edit';

    it('should return the edit view with vitamin d3 data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminD3 = VitaminD3::factory()->for($medicalFile)->create([
            'twenty_five_hydroxyvitamin_d3' => 31.7,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('vitamin-d3.edit', $vitaminD3));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.vitamin_d3_pages.edit.head_title', 'Edit Vitamin D3 record')
            ->has('vitaminD3', fn (AssertableInertia $item) => $item
                ->where('twenty_five_hydroxyvitamin_d3', 31.7)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('vitamin-d3.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of VitaminD3Controller', function () {
    it('should update the vitamin d3 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminD3 = VitaminD3::factory()->for($medicalFile)->create([
            'twenty_five_hydroxyvitamin_d3' => 26.2,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'twenty_five_hydroxyvitamin_d3' => 39.8,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('vitamin-d3.update', $vitaminD3), $updatedData);

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', [...$updatedData, 'id' => $vitaminD3->id]);
    });

    it('should not update a vitamin d3 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminD3 = VitaminD3::factory()->for($ownerMedicalFile)->create([
            'twenty_five_hydroxyvitamin_d3' => 22.5,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'twenty_five_hydroxyvitamin_d3' => 45.0,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('vitamin-d3.index'))
            ->actingAs($anotherUser)
            ->put(route('vitamin-d3.update', $vitaminD3), $updatedData);

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', [
            'id' => $vitaminD3->id,
            'twenty_five_hydroxyvitamin_d3' => 22.5,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('vitamin-d3.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of VitaminD3Controller', function () {
    it('should delete the vitamin d3 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminD3 = VitaminD3::factory()->for($medicalFile)->create([
            'twenty_five_hydroxyvitamin_d3' => 29.4,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('vitamin-d3.destroy', $vitaminD3));

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseMissing('vitamin_d3_s', ['id' => $vitaminD3->id]);
    });

    it('should not delete a vitamin d3 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminD3 = VitaminD3::factory()->for($ownerMedicalFile)->create([
            'twenty_five_hydroxyvitamin_d3' => 24.6,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('vitamin-d3.index'))
            ->actingAs($anotherUser)
            ->delete(route('vitamin-d3.destroy', $vitaminD3));

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', ['id' => $vitaminD3->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('vitamin-d3.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('rejects missing fields without saving a record', function () {
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('vitamin-d3.store'), [])
        ->assertSessionHasErrors(['twenty_five_hydroxyvitamin_d3', 'report_date']);

    $this->assertDatabaseCount('vitamin_d3_s', 0);
});

it('rejects invalid results on creation and update without changing stored data', function (string $field, mixed $value, string $message, string $method) {
    $record = VitaminD3::factory()->create(['twenty_five_hydroxyvitamin_d3' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $data = [...$record->only(['twenty_five_hydroxyvitamin_d3', 'report_date']), $field => $value];
    $route = $method === 'post' ? route('vitamin-d3.store') : route('vitamin-d3.update', $record);

    $this->actingAs($record->medicalFile->user)->{$method}($route, $data)
        ->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('vitamin_d3_s', 1);
    $this->assertDatabaseHas('vitamin_d3_s', $original);
})->with([
    'negative twenty_five_hydroxyvitamin_d3' => ['twenty_five_hydroxyvitamin_d3', -1, 'The twenty five hydroxyvitamin d3 field must be at least 0.'],
    'non-numeric twenty_five_hydroxyvitamin_d3' => ['twenty_five_hydroxyvitamin_d3', 'invalid', 'The twenty five hydroxyvitamin d3 field must be a number.'],
    'invalid report date' => ['report_date', 'invalid', 'The report date field must be a valid date.'],
])->with(['post', 'put']);

it('accepts zero measurements and ignores a submitted medical file owner', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['twenty_five_hydroxyvitamin_d3' => 0, 'report_date' => '2026-08-10'];

    $this->actingAs($user)->post(route('vitamin-d3.store'), [...$data, 'medical_file_id' => $otherMedicalFile->id])
        ->assertRedirect(route('vitamin-d3.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('vitamin_d3_s', [...$data, 'medical_file_id' => $medicalFile->id]);
    $this->assertDatabaseMissing('vitamin_d3_s', ['medical_file_id' => $otherMedicalFile->id]);
});

it('keeps the medical file owner when updating measurements', function () {
    $record = VitaminD3::factory()->create();
    $otherMedicalFile = User::factory()->create()->medicalFile()->create();
    $data = ['twenty_five_hydroxyvitamin_d3' => 20, 'report_date' => '2026-08-10'];

    $this->actingAs($record->medicalFile->user)->put(route('vitamin-d3.update', $record), [
        ...$data,
        'medical_file_id' => $otherMedicalFile->id,
    ])->assertRedirect(route('vitamin-d3.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseHas('vitamin_d3_s', [...$data, 'id' => $record->id, 'medical_file_id' => $record->medical_file_id]);
});

it('returns not found for missing records', function (string $method, string $action) {
    $this->actingAs(User::factory()->create())
        ->{$method}(route('vitamin-d3.'.$action, 999), ['twenty_five_hydroxyvitamin_d3' => 20, 'report_date' => '2026-08-10'])
        ->assertNotFound();
})->with([
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('sorts and paginates only the authenticated users records', function () {
    $record = VitaminD3::factory()->create(['twenty_five_hydroxyvitamin_d3' => 80]);
    VitaminD3::factory()->for($record->medicalFile)->create(['twenty_five_hydroxyvitamin_d3' => 20]);
    VitaminD3::factory()->create(['twenty_five_hydroxyvitamin_d3' => 90]);

    $this->actingAs($record->medicalFile->user)
        ->get(route('vitamin-d3.index', ['sort_by' => 'twenty_five_hydroxyvitamin_d3', 'sort_dir' => 'desc', 'per_page' => 1]))
        ->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('vitaminD3s.data', 1)
        ->where('vitaminD3s.total', 2)
        ->where('vitaminD3s.data.0.id', $record->id)
        );
});

it('filters records by date without including other owners', function (string $field) {
    $record = VitaminD3::factory()->create([$field => '2026-08-10 12:00:00']);
    VitaminD3::factory()->for($record->medicalFile)->create([$field => '2026-08-09 12:00:00']);
    VitaminD3::factory()->create([$field => '2026-08-10 12:00:00']);
    $date = CarbonImmutable::parse('2026-08-10')->getTimestampMs();

    $this->actingAs($record->medicalFile->user)->get(route('vitamin-d3.index', [
        'filters' => [$field => [$date, $date]],
    ]))->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->has('vitaminD3s.data', 1)
        ->where('vitaminD3s.data.0.id', $record->id)
    );
})->with(['report_date', 'created_at']);

it('preserves stored data and returns an error toast when persistence fails', function (string $event, string $method, string $action) {
    $record = VitaminD3::factory()->create(['twenty_five_hydroxyvitamin_d3' => 20, 'report_date' => '2026-08-10']);
    $original = $record->getRawOriginal();
    $eventName = 'eloquent.'.$event.': '.VitaminD3::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $action === 'store' ? route('vitamin-d3.store') : route('vitamin-d3.'.$action, $record);

        $this->from(route('vitamin-d3.index'))->actingAs($record->medicalFile->user)
            ->{$method}($route, [...$record->only(['twenty_five_hydroxyvitamin_d3', 'report_date']), 'twenty_five_hydroxyvitamin_d3' => 30])
            ->assertRedirect(route('vitamin-d3.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('vitamin_d3_s', 1);
        $this->assertDatabaseHas('vitamin_d3_s', $original);
    } finally {
        Event::forget($eventName);
    }
})->with([
    'creation' => ['creating', 'post', 'store'],
    'update' => ['updating', 'put', 'update'],
    'deletion' => ['deleting', 'delete', 'destroy'],
]);

it('removes results when their medical file is deleted', function () {
    $record = VitaminD3::factory()->create();

    $record->medicalFile->delete();

    $this->assertModelMissing($record);
});
