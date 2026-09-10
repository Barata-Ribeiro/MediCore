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
    $this->actingAsGuest()->{$method}(route('exercises.'.$action, 999), [])
        ->assertRedirect(route('login'));
})->with([
    'create' => ['get', 'create'],
    'store' => ['post', 'store'],
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
    'destroy' => ['delete', 'destroy'],
]);

it('rejects invalid names on creation and update without changing the catalog', function (mixed $name, string $message, string $method) {
    $record = Exercise::factory()->create(['name' => 'Original name']);
    $route = $method === 'post' ? route('exercises.store') : route('exercises.update', $record);

    $this->actingAs($record->user)->{$method}($route, ['name' => $name])
        ->assertSessionHasErrors(['name' => $message]);

    $this->assertDatabaseCount('exercises', 1);
    $this->assertDatabaseHas('exercises', ['id' => $record->id, 'name' => 'Original name']);
})->with([
    'missing' => [null, 'The name field is required.'],
    'non-string' => [123, 'The name field must be a string.'],
    'too long' => [str_repeat('a', 256), 'The name field must not be greater than 255 characters.'],
])->with(['post', 'put']);

it('rejects duplicate names within the owners catalog', function (string $method) {
    $record = Exercise::factory()->create(['name' => 'Existing name']);
    $target = Exercise::factory()->for($record->user)->create(['name' => 'Original name']);
    $route = $method === 'post' ? route('exercises.store') : route('exercises.update', $target);

    $this->actingAs($record->user)->{$method}($route, ['name' => 'Existing name'])
        ->assertSessionHasErrors(['name' => 'The name has already been taken.']);

    $this->assertDatabaseCount('exercises', 2);
    $this->assertDatabaseHas('exercises', ['id' => $target->id, 'name' => 'Original name']);
})->with(['post', 'put']);

it('allows the same name in another catalog and ignores a submitted owner', function () {
    $record = Exercise::factory()->create(['name' => 'Shared name']);
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('exercises.store'), ['name' => 'Shared name', 'user_id' => $record->user_id])
        ->assertRedirect(route('exercises.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertDatabaseCount('exercises', 2);
    $this->assertDatabaseHas('exercises', ['name' => 'Shared name', 'user_id' => $user->id]);
});

it('preserves another users record when deletion is attempted', function () {
    $record = Exercise::factory()->create();

    $this->from(route('exercises.index'))->actingAs(User::factory()->create())
        ->delete(route('exercises.destroy', $record))
        ->assertRedirect(route('exercises.index'))
        ->assertInertiaFlash('toast.type', 'error');

    $this->assertModelExists($record);
});

it('removes the owners record and its catalog links', function () {
    $muscleGroup = MuscleGroup::factory()->create();
    $exercise = Exercise::factory()->for($muscleGroup->user)->hasAttached($muscleGroup)->create();
    $record = $exercise;

    $this->actingAs($record->user)->delete(route('exercises.destroy', $record))
        ->assertRedirect(route('exercises.index'))
        ->assertInertiaFlash('toast.type', 'success');

    $this->assertModelMissing($record);
    $this->assertModelExists($muscleGroup);
    $this->assertDatabaseMissing('exercise_muscle_groups', ['exercise_id' => $exercise->id, 'muscle_group_id' => $muscleGroup->id]);
});

describe('tests for ExerciseController', function () {
    it('shows only authenticated user exercises', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Exercise::factory()->for($user)->create(['name' => 'Bench Press']);
        Exercise::factory()->for($otherUser)->create(['name' => 'Other Exercise']);

        $response = $this->actingAs($user)->get(route('exercises.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('fitness/exercise/index')
            ->has('exercises.data', 1)
            ->where('exercises.data.0.name', 'Bench Press')
        );
    });

    it('paginates and sorts exercises by muscle group name', function () {
        $user = User::factory()->create();
        $shoulders = MuscleGroup::factory()->for($user)->create(['name' => 'Shoulders']);
        $back = MuscleGroup::factory()->for($user)->create(['name' => 'Back']);

        $press = Exercise::factory()->for($user)->create(['name' => 'Overhead Press']);
        $row = Exercise::factory()->for($user)->create(['name' => 'Barbell Row']);
        $press->muscleGroups()->attach($shoulders);
        $row->muscleGroups()->attach($back);

        $response = $this->actingAs($user)->get(route('exercises.index', [
            'per_page' => 1,
            'sort_by' => 'muscle_group_name',
            'sort_dir' => 'asc',
        ]));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->where('exercises.total', 2)
            ->where('exercises.data.0.name', 'Barbell Row')
            ->where('exercises.data.0.muscle_groups.0.name', 'Back')
        );

    });

    it('stores exercise in authenticated user catalog and syncs muscle groups', function () {
        $user = User::factory()->create();
        $muscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);

        $response = $this->actingAs($user)->post(route('exercises.store'), [
            'name' => 'Incline Bench Press',
            'description' => 'Use controlled eccentric phase.',
            'video_url' => 'https://example.com/exercise',
            'muscle_group_ids' => [$muscleGroup->id],
        ]);

        $response->assertRedirect(route('exercises.index'));

        $exercise = Exercise::query()->where('name', 'Incline Bench Press')->firstOrFail();

        expect($exercise->user_id)->toBe($user->id);
        $this->assertDatabaseHas('exercise_muscle_groups', [
            'exercise_id' => $exercise->id,
            'muscle_group_id' => $muscleGroup->id,
        ]);
    });

    it('returns the create view with muscle groups for authenticated users', function () {
        $user = User::factory()->create();
        MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);

        $response = $this->actingAs($user)->get(route('exercises.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('fitness/exercise/create')
            ->has('muscleGroups', 1)
        );
    });

    it('returns the edit view with exercise and muscle groups for authenticated users', function () {
        $user = User::factory()->create();
        $muscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);
        $exercise = Exercise::factory()->for($user)->create(['name' => 'Bench Press']);
        $exercise->muscleGroups()->attach($muscleGroup->id);

        $response = $this->actingAs($user)->get(route('exercises.edit', $exercise));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('fitness/exercise/edit')
            ->where('exercise.id', $exercise->id)
            ->has('muscleGroups', 1)
        );
    });

    it('blocks storing exercise with muscle group from another user', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherMuscleGroup = MuscleGroup::factory()->for($otherUser)->create(['name' => 'Quadriceps']);

        $response = $this->actingAs($user)->post(route('exercises.store'), [
            'name' => 'Hack Squat',
            'muscle_group_ids' => [$otherMuscleGroup->id],
        ]);

        $response->assertSessionHasErrors('muscle_group_ids.0');
        $this->assertDatabaseCount('exercises', 0);
        $this->assertDatabaseCount('exercise_muscle_groups', 0);
    });

    it('updates an exercise while keeping its current name', function () {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->for($user)->create(['name' => 'Bench Press',
            'description' => 'Original description.']);

        $response = $this->actingAs($user)->put(route('exercises.update', $exercise), [
            'name' => 'Bench Press',
            'description' => 'Updated description.',
        ]);

        $response->assertRedirect(route('exercises.index'));
        $this->assertDatabaseHas('exercises', [
            'id' => $exercise->id,
            'name' => 'Bench Press',
            'description' => 'Updated description.',
        ]);
    });

    it('does not allow updating another user exercise', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $exercise = Exercise::factory()->for($otherUser)->create(['name' => 'Row']);

        $response = $this->actingAs($user)->put(route('exercises.update', $exercise), [
            'name' => 'Updated',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('exercises', ['id' => $exercise->id, 'name' => 'Row']);
    });

    it('redirects guests to login', function () {
        $response = $this->actingAsGuest()->get(route('exercises.index'));

        $response->assertRedirect(route('login'));
    });
});
