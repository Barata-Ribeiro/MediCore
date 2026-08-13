<?php

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->withoutVite();
});

describe('tests for ExerciseController', function () {
    it('shows only authenticated user exercises', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Exercise::create(['name' => 'Bench Press', 'user_id' => $user->id]);
        Exercise::create(['name' => 'Other Exercise', 'user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('exercises.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('fitness/exercise/index')
            ->has('exercises', 1)
        );
    });

    it('stores exercise in authenticated user catalog and syncs muscle groups', function () {
        $user = User::factory()->create();
        $muscleGroup = MuscleGroup::create(['name' => 'Pectorals', 'user_id' => $user->id]);

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
        MuscleGroup::create(['name' => 'Pectorals', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('exercises.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('fitness/exercise/create')
            ->has('muscleGroups', 1)
        );
    });

    it('returns the edit view with exercise and muscle groups for authenticated users', function () {
        $user = User::factory()->create();
        $muscleGroup = MuscleGroup::create(['name' => 'Pectorals', 'user_id' => $user->id]);
        $exercise = Exercise::create(['name' => 'Bench Press', 'user_id' => $user->id]);
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

        $otherMuscleGroup = MuscleGroup::create(['name' => 'Quadriceps', 'user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->post(route('exercises.store'), [
            'name' => 'Hack Squat',
            'muscle_group_ids' => [$otherMuscleGroup->id],
        ]);

        $response->assertSessionHasErrors('muscle_group_ids.0');
    });

    it('does not allow updating another user exercise', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $exercise = Exercise::create(['name' => 'Row', 'user_id' => $otherUser->id]);

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
