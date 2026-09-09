<?php

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->withoutVite();
    $this->travelTo('2026-08-10 12:00:00');
});

it('redirects guests from form and write routes', function (string $method, string $action) {
    $this->actingAsGuest()->{$method}(route('muscle-groups.'.$action, 999), [])
        ->assertRedirect(route('login'));
})->with([
    'create' => ['get', 'create'],
    'store' => ['post', 'store'],
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('rejects invalid names on creation and update without changing the catalog', function (mixed $name, string $message, string $method) {
    $record = MuscleGroup::factory()->create(['name' => 'Original name']);
    $route = $method === 'post' ? route('muscle-groups.store') : route('muscle-groups.update', $record);

    $this->actingAs($record->user)->{$method}($route, ['name' => $name])
        ->assertSessionHasErrors(['name' => $message]);

    $this->assertDatabaseCount('muscle_groups', 1);
    $this->assertDatabaseHas('muscle_groups', ['id' => $record->id, 'name' => 'Original name']);
})->with([
    'missing' => [null, 'The name field is required.'],
    'non-string' => [123, 'The name field must be a string.'],
    'too long' => [str_repeat('a', 256), 'The name field must not be greater than 255 characters.'],
])->with(['post', 'put']);

it('rejects duplicate names within the owners catalog', function (string $method) {
    $record = MuscleGroup::factory()->create(['name' => 'Existing name']);
    $target = MuscleGroup::factory()->for($record->user)->create(['name' => 'Original name']);
    $route = $method === 'post' ? route('muscle-groups.store') : route('muscle-groups.update', $target);

    $this->actingAs($record->user)->{$method}($route, ['name' => 'Existing name'])
        ->assertSessionHasErrors(['name' => 'The name has already been taken.']);

    $this->assertDatabaseCount('muscle_groups', 2);
    $this->assertDatabaseHas('muscle_groups', ['id' => $target->id, 'name' => 'Original name']);
})->with(['post', 'put']);

it('allows the same name in another catalog and ignores a submitted owner', function () {
    $record = MuscleGroup::factory()->create(['name' => 'Shared name']);
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('muscle-groups.store'), ['name' => 'Shared name', 'user_id' => $record->user_id])
        ->assertRedirect(route('muscle-groups.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseCount('muscle_groups', 2);
    $this->assertDatabaseHas('muscle_groups', ['name' => 'Shared name', 'user_id' => $user->id]);
});

it('preserves another users record when deletion is attempted', function () {
    $record = MuscleGroup::factory()->create();

    $this->from(route('muscle-groups.index'))->actingAs(User::factory()->create())
        ->delete(route('muscle-groups.destroy', $record))
        ->assertRedirect(route('muscle-groups.index'))
        ->assertInertiaFlash('toast.type', 'error');

    $this->assertModelExists($record);
});

it('removes the owners record and its catalog links', function () {
    $muscleGroup = MuscleGroup::factory()->create();
    $exercise = Exercise::factory()->for($muscleGroup->user)->hasAttached($muscleGroup)->create();
    $record = $muscleGroup;

    $this->actingAs($record->user)->delete(route('muscle-groups.destroy', $record))
        ->assertRedirect(route('muscle-groups.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertModelMissing($record);
    $this->assertModelExists($exercise);
    $this->assertDatabaseMissing('exercise_muscle_groups', ['exercise_id' => $exercise->id, 'muscle_group_id' => $muscleGroup->id]);
});

describe('tests for MuscleGroupController', function () {
    it('shows only authenticated user muscle groups', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);
        MuscleGroup::factory()->for($otherUser)->create(['name' => 'Quadriceps']);

        $response = $this->actingAs($user)->get(route('muscle-groups.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('fitness/muscle-group/index')
            ->has('muscleGroups.data', 1)
            ->where('muscleGroups.data.0.name', 'Pectorals')
        );
    });

    it('paginates and sorts muscle groups by exercises count', function () {
        $user = User::factory()->create();

        $chest = MuscleGroup::factory()->for($user)->create(['name' => 'Chest']);
        $back = MuscleGroup::factory()->for($user)->create(['name' => 'Back']);
        Exercise::factory()->for($user)->hasAttached($back)->create(['name' => 'Row']);

        $response = $this->actingAs($user)->get(route('muscle-groups.index', [
            'per_page' => 1,
            'sort_by' => 'exercises_count',
            'sort_dir' => 'desc',
        ]));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->where('muscleGroups.total', 2)
            ->where('muscleGroups.data.0.name', 'Back')
            ->where('muscleGroups.data.0.exercises_count', 1)
        );
    });

    it('filters muscle groups by exercises count range', function () {
        $user = User::factory()->create();

        $chest = MuscleGroup::factory()->for($user)->create(['name' => 'Chest']);
        $back = MuscleGroup::factory()->for($user)->create(['name' => 'Back']);
        Exercise::factory()->for($user)->hasAttached($back)->create(['name' => 'Row']);

        $response = $this->actingAs($user)->get(route('muscle-groups.index', [
            'filters' => ['exercises_count' => [1, 5]],
        ]));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->where('muscleGroups.total', 1)
            ->where('muscleGroups.data.0.name', 'Back')
        );
    });

    it('filters muscle groups by exercises count range starting at zero', function () {
        $user = User::factory()->create();

        $chest = MuscleGroup::factory()->for($user)->create(['name' => 'Chest']);
        $back = MuscleGroup::factory()->for($user)->create(['name' => 'Back']);
        Exercise::factory()->for($user)->hasAttached($back)->create(['name' => 'Row']);

        $response = $this->actingAs($user)->get(route('muscle-groups.index', [
            'filters' => 'exercises_count:0,100',
        ]));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->where('muscleGroups.total', 2)
        );
    });

    it('stores muscle group in authenticated user catalog', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('muscle-groups.store'), [
            'name' => 'Hamstrings',
        ]);

        $response->assertRedirect(route('muscle-groups.index'));
        $this->assertDatabaseHas('muscle_groups', [
            'name' => 'Hamstrings',
            'user_id' => $user->id,
        ]);
    });

    it('updates a muscle group while keeping its current name', function () {
        $user = User::factory()->create();
        $muscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Back']);

        $response = $this->actingAs($user)->put(route('muscle-groups.update', $muscleGroup), [
            'name' => 'Back',
        ]);

        $response->assertRedirect(route('muscle-groups.index'));
        $this->assertDatabaseHas('muscle_groups', [
            'id' => $muscleGroup->id,
            'name' => 'Back',
        ]);
    });

    it('does not allow updating another user muscle group', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $muscleGroup = MuscleGroup::factory()->for($otherUser)->create(['name' => 'Back']);

        $response = $this->actingAs($user)->put(route('muscle-groups.update', $muscleGroup), [
            'name' => 'Updated',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('muscle_groups', ['id' => $muscleGroup->id, 'name' => 'Back']);
    });

    it('redirects guests to login', function () {
        $response = $this->actingAsGuest()->get(route('muscle-groups.index'));

        $response->assertRedirect(route('login'));
    });
});
