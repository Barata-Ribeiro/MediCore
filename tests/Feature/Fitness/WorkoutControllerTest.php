<?php

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->withoutVite();
});

function workoutPayload(Exercise $exercise, MuscleGroup $muscleGroup): array
{
    return [
        'filled_at' => now()->toDateString(),
        'next_change_at' => now()->addWeeks(4)->toDateString(),
        'goal' => 'Build muscle',
        'method' => 'Upper/Lower split',
        'rest_between_sets' => 90,
        'rest_between_exercises' => 150,
        'is_active' => true,
        'sections' => [
            [
                'name' => 'Upper A',
                'order' => 1,
                'exercises' => [
                    [
                        'exercise_id' => $exercise->id,
                        'muscle_group_id' => $muscleGroup->id,
                        'code' => 'A1',
                        'order' => 1,
                        'sets' => 4,
                        'reps' => '8-10',
                        'load' => 80,
                        'load_unit' => 'kg',
                        'rest_seconds' => 120,
                        'notes' => 'Control eccentric',
                    ],
                ],
            ],
        ],
    ];
}

describe('tests for the "index" method of WorkoutController', function () {
    $componentName = 'fitness/workout/index';

    it('should return successful response with user workouts only', function () use ($componentName) {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $user->workouts()->create(['goal' => 'User workout']);
        $otherUser->workouts()->create(['goal' => 'Other workout']);

        $response = $this->actingAs($user)->get(route('workouts.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component($componentName)
            ->has('workouts.data', 1)
            ->where('workouts.data.0.goal', 'User workout')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('workouts.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of WorkoutController', function () {
    $componentName = 'fitness/workout/create';

    it('should return the create view with form options for authenticated users', function () use ($componentName) {
        Exercise::create(['name' => 'Bench Press']);
        MuscleGroup::create(['name' => 'Pectorals']);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('workouts.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component($componentName)
            ->has('formOptions.exercises', 1)
            ->has('formOptions.muscleGroups', 1)
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('workouts.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of WorkoutController', function () {
    it('should store workout with sections and exercises and redirect to index', function () {
        $user = User::factory()->create();
        $exercise = Exercise::create(['name' => 'Bench Press']);
        $muscleGroup = MuscleGroup::create(['name' => 'Pectorals']);

        $payload = workoutPayload($exercise, $muscleGroup);

        $response = $this->actingAs($user)->post(route('workouts.store'), $payload);

        $response->assertRedirect(route('workouts.index'));

        $this->assertDatabaseHas('workouts', [
            'user_id' => $user->id,
            'goal' => 'Build muscle',
            'method' => 'Upper/Lower split',
        ]);

        $workout = Workout::query()->where('user_id', $user->id)->firstOrFail();

        $this->assertDatabaseHas('workout_sections', [
            'workout_id' => $workout->id,
            'name' => 'Upper A',
            'order' => 1,
        ]);

        $sectionId = $workout->sections()->value('id');

        $this->assertDatabaseHas('workout_exercises', [
            'workout_section_id' => $sectionId,
            'exercise_id' => $exercise->id,
            'muscle_group_id' => $muscleGroup->id,
            'code' => 'A1',
            'sets' => 4,
            'reps' => '8-10',
            'load_unit' => 'kg',
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('workouts.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "show" method of WorkoutController', function () {
    $componentName = 'fitness/workout/show';

    it('should return the show view for workout owner', function () use ($componentName) {
        $user = User::factory()->create();
        $workout = $user->workouts()->create(['goal' => 'Maintain']);

        $response = $this->actingAs($user)->get(route('workouts.show', $workout));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component($componentName)
            ->where('workout.id', $workout->id)
        );
    });

    it('should not allow showing another user workout', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $workout = $otherUser->workouts()->create(['goal' => 'Other']);

        $response = $this->actingAs($user)->get(route('workouts.show', $workout));

        $response->assertRedirect();
    });
});

describe('tests for the "edit" method of WorkoutController', function () {
    $componentName = 'fitness/workout/edit';

    it('should return edit view for workout owner', function () use ($componentName) {
        $user = User::factory()->create();
        $workout = $user->workouts()->create(['goal' => 'Maintain']);
        Exercise::create(['name' => 'Squat']);
        MuscleGroup::create(['name' => 'Quadriceps']);

        $response = $this->actingAs($user)->get(route('workouts.edit', $workout));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component($componentName)
            ->where('workout.id', $workout->id)
            ->has('formOptions.exercises', 1)
            ->has('formOptions.muscleGroups', 1)
        );
    });

    it('should not allow editing another user workout', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $workout = $otherUser->workouts()->create(['goal' => 'Other']);

        $response = $this->actingAs($user)->get(route('workouts.edit', $workout));

        $response->assertRedirect();
    });
});

describe('tests for the "update" method of WorkoutController', function () {
    it('should update workout and synchronize nested sections and exercises', function () {
        $user = User::factory()->create();
        $exerciseA = Exercise::create(['name' => 'Bench Press']);
        $exerciseB = Exercise::create(['name' => 'Squat']);
        $muscleGroup = MuscleGroup::create(['name' => 'Pectorals']);

        $workout = $user->workouts()->create([
            'goal' => 'Initial goal',
            'method' => 'Initial method',
            'is_active' => true,
        ]);

        $section = $workout->sections()->create([
            'name' => 'Initial section',
            'order' => 1,
        ]);

        $workoutExercise = $section->exercises()->create([
            'exercise_id' => $exerciseA->id,
            'muscle_group_id' => $muscleGroup->id,
            'code' => 'A1',
            'order' => 1,
            'sets' => 3,
            'reps' => '8-12',
            'load' => 70,
            'load_unit' => 'kg',
        ]);

        $response = $this->actingAs($user)->put(route('workouts.update', $workout), [
            'goal' => 'Updated goal',
            'method' => 'Updated method',
            'is_active' => false,
            'sections' => [
                [
                    'id' => $section->id,
                    'name' => 'Updated section',
                    'order' => 1,
                    'exercises' => [
                        [
                            'id' => $workoutExercise->id,
                            'exercise_id' => $exerciseB->id,
                            'muscle_group_id' => $muscleGroup->id,
                            'code' => 'B2',
                            'order' => 1,
                            'sets' => 5,
                            'reps' => '5',
                            'load' => 100,
                            'load_unit' => 'kg',
                            'rest_seconds' => 180,
                            'notes' => 'Heavy set',
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertRedirect(route('workouts.index'));

        $this->assertDatabaseHas('workouts', [
            'id' => $workout->id,
            'goal' => 'Updated goal',
            'method' => 'Updated method',
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('workout_sections', [
            'id' => $section->id,
            'name' => 'Updated section',
        ]);

        $this->assertDatabaseHas('workout_exercises', [
            'id' => $workoutExercise->id,
            'exercise_id' => $exerciseB->id,
            'code' => 'B2',
            'sets' => 5,
            'reps' => '5',
        ]);
    });

    it('should not allow updating another user workout', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $workout = $otherUser->workouts()->create(['goal' => 'Other']);

        $response = $this->actingAs($user)->put(route('workouts.update', $workout), [
            'goal' => 'Attempted update',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('workouts', [
            'id' => $workout->id,
            'goal' => 'Other',
        ]);
    });
});

describe('tests for the "destroy" method of WorkoutController', function () {
    it('should delete workout and related sections and exercises and redirect to index', function () {
        $user = User::factory()->create();
        $exercise = Exercise::create(['name' => 'Deadlift']);

        $workout = $user->workouts()->create(['goal' => 'Delete me']);
        $section = $workout->sections()->create(['name' => 'Section', 'order' => 1]);
        $exerciseRow = $section->exercises()->create([
            'exercise_id' => $exercise->id,
            'order' => 1,
            'sets' => 3,
            'reps' => '8-12',
            'load_unit' => 'kg',
        ]);

        $response = $this->actingAs($user)->delete(route('workouts.destroy', $workout));

        $response->assertRedirect(route('workouts.index'));
        $this->assertDatabaseMissing('workouts', ['id' => $workout->id]);
        $this->assertDatabaseMissing('workout_sections', ['id' => $section->id]);
        $this->assertDatabaseMissing('workout_exercises', ['id' => $exerciseRow->id]);
    });

    it('should not allow deleting another user workout', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $workout = $otherUser->workouts()->create(['goal' => 'Other']);

        $response = $this->actingAs($user)->delete(route('workouts.destroy', $workout));

        $response->assertRedirect();
        $this->assertDatabaseHas('workouts', ['id' => $workout->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('workouts.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
