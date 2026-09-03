<?php

use App\Models\Fitness\MuscleGroup;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->withoutVite();
});

describe('tests for MuscleGroupController', function () {
    it('shows only authenticated user muscle groups', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        MuscleGroup::create(['name' => 'Pectorals', 'user_id' => $user->id]);
        MuscleGroup::create(['name' => 'Quadriceps', 'user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('muscle-groups.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('fitness/muscle-group/index')
            ->has('muscleGroups.data', 1)
        );
    });

    it('paginates and sorts muscle groups by exercises count', function () {
        $user = User::factory()->create();

        $chest = MuscleGroup::create(['name' => 'Chest', 'user_id' => $user->id]);
        $back = MuscleGroup::create(['name' => 'Back', 'user_id' => $user->id]);
        $back->exercises()->create(['name' => 'Row', 'user_id' => $user->id]);

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

        $chest = MuscleGroup::create(['name' => 'Chest', 'user_id' => $user->id]);
        $back = MuscleGroup::create(['name' => 'Back', 'user_id' => $user->id]);
        $back->exercises()->create(['name' => 'Row', 'user_id' => $user->id]);

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

        $chest = MuscleGroup::create(['name' => 'Chest', 'user_id' => $user->id]);
        $back = MuscleGroup::create(['name' => 'Back', 'user_id' => $user->id]);
        $back->exercises()->create(['name' => 'Row', 'user_id' => $user->id]);

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
        $muscleGroup = MuscleGroup::create(['name' => 'Back', 'user_id' => $user->id]);

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

        $muscleGroup = MuscleGroup::create(['name' => 'Back', 'user_id' => $otherUser->id]);

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
