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
            ->has('muscleGroups', 1)
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
