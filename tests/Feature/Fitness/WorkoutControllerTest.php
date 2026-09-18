<?php

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use App\Models\Fitness\WorkoutExercise;
use App\Models\Fitness\WorkoutSection;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->withoutVite();
    $this->travelTo('2026-08-10 12:00:00');
});

it('redirects guests from the remaining workout routes', function (string $method, string $action) {
    $this->actingAsGuest()->{$method}(route('workouts.'.$action, 999), [])
        ->assertRedirect(route('login'));
})->with([
    'show' => ['get', 'show'],
    'edit' => ['get', 'edit'],
    'update' => ['put', 'update'],
]);

it('rejects invalid workout data without changing existing sections or exercises', function (string $field, mixed $value, string $method) {
    $row = WorkoutExercise::factory()->create();
    $workout = $row->section->workout;
    $muscleGroup = MuscleGroup::factory()->for($workout->user)->create();
    $row->exercise->muscleGroups()->attach($muscleGroup);
    $payload = workoutPayload($row->exercise, $muscleGroup);
    data_set($payload, $field, $value);
    $originalWorkout = $workout->getRawOriginal();
    $originalRow = $row->getRawOriginal();
    $route = $method === 'post' ? route('workouts.store') : route('workouts.update', $workout);

    $this->actingAs($workout->user)->{$method}($route, $payload)
        ->assertSessionHasErrors($field);

    $this->assertDatabaseCount('workouts', 1);
    $this->assertDatabaseCount('workout_sections', 1);
    $this->assertDatabaseCount('workout_exercises', 1);
    $this->assertDatabaseHas('workouts', $originalWorkout);
    $this->assertDatabaseHas('workout_exercises', $originalRow);
})->with([
    'invalid date' => ['filled_at', 'invalid'],
    'change before start' => ['next_change_at', '2026-08-09'],
    'negative rest' => ['rest_between_sets', -1],
    'missing section name' => ['sections.0.name', null],
    'negative section order' => ['sections.0.order', -1],
    'no sets' => ['sections.0.exercises.0.sets', 0],
    'negative load' => ['sections.0.exercises.0.load', -1],
    'missing reps' => ['sections.0.exercises.0.reps', null],
    'missing unit' => ['sections.0.exercises.0.load_unit', null],
])->with(['post', 'put']);

it('rolls back the workout and nested records when an exercise cannot be saved', function (string $method) {
    $row = WorkoutExercise::factory()->create();
    $workout = $row->section->workout;
    $muscleGroup = MuscleGroup::factory()->for($workout->user)->create();
    $row->exercise->muscleGroups()->attach($muscleGroup);
    $payload = workoutPayload($row->exercise, $muscleGroup);
    $original = $workout->getRawOriginal();
    $eventName = 'eloquent.creating: '.WorkoutExercise::class;
    Event::listen($eventName, function () {
        throw new RuntimeException('Persistence unavailable');
    });

    try {
        $route = $method === 'post' ? route('workouts.store') : route('workouts.update', $workout);

        $this->from(route('workouts.index'))->actingAs($workout->user)->{$method}($route, $payload)
            ->assertRedirect(route('workouts.index'))
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertDatabaseCount('workouts', 1);
        $this->assertDatabaseCount('workout_sections', 1);
        $this->assertDatabaseCount('workout_exercises', 1);
        $this->assertDatabaseHas('workouts', $original);
        $this->assertModelExists($row);
    } finally {
        Event::forget($eventName);
    }
})->with(['post', 'put']);

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

        Workout::factory()->for($user)->create(['goal' => 'User workout']);
        Workout::factory()->for($otherUser)->create(['goal' => 'Other workout']);

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
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $exercise = Exercise::factory()->for($user)->create(['name' => 'Bench Press']);
        $muscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);
        $exercise->muscleGroups()->attach($muscleGroup->id);

        Exercise::factory()->for($otherUser)->create(['name' => 'Other exercise']);
        MuscleGroup::factory()->for($otherUser)->create(['name' => 'Other group']);

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
    it('should store workout with sections and exercises and open the saved plan', function () {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->for($user)->create(['name' => 'Bench Press']);
        $muscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);
        $exercise->muscleGroups()->attach($muscleGroup->id);

        $payload = workoutPayload($exercise, $muscleGroup);

        $response = $this->actingAs($user)->post(route('workouts.store'), $payload);

        $response->assertRedirect(route('workouts.show', Workout::query()->whereBelongsTo($user)->sole()));

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

    it('should reject muscle groups not linked to the selected exercise', function () {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->for($user)->create(['name' => 'Bench Press']);
        $linkedMuscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);
        $mismatchedMuscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Quadriceps']);

        $exercise->muscleGroups()->attach($linkedMuscleGroup->id);

        $payload = workoutPayload($exercise, $mismatchedMuscleGroup);

        $response = $this->actingAs($user)->post(route('workouts.store'), $payload);

        $response
            ->assertSessionHasErrors('sections.0.exercises.0.muscle_group_id')
            ->assertRedirect();

        $this->assertDatabaseMissing('workouts', [
            'user_id' => $user->id,
            'goal' => 'Build muscle',
        ]);
    });

    it('should reject exercises from another user catalog', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $exercise = Exercise::factory()->for($otherUser)->create(['name' => 'Bench Press']);
        $muscleGroup = MuscleGroup::factory()->for($otherUser)->create(['name' => 'Pectorals']);
        $exercise->muscleGroups()->attach($muscleGroup->id);

        $payload = workoutPayload($exercise, $muscleGroup);

        $response = $this->actingAs($user)->post(route('workouts.store'), $payload);

        $response
            ->assertSessionHasErrors('sections.0.exercises.0.exercise_id')
            ->assertRedirect();
    });
});

describe('tests for the "show" method of WorkoutController', function () {
    $componentName = 'fitness/workout/show';

    it('should return the show view for workout owner', function () use ($componentName) {
        $user = User::factory()->create();
        $workout = Workout::factory()->for($user)->create(['goal' => 'Maintain']);

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
        $workout = Workout::factory()->for($otherUser)->create(['goal' => 'Other']);

        $response = $this->actingAs($user)->get(route('workouts.show', $workout));

        $response->assertRedirect();
    });
});

describe('tests for the "edit" method of WorkoutController', function () {
    $componentName = 'fitness/workout/edit';

    it('should return edit view for workout owner', function () use ($componentName) {
        $user = User::factory()->create();
        $workout = Workout::factory()->for($user)->create(['goal' => 'Maintain']);
        $exercise = Exercise::factory()->for($user)->create(['name' => 'Squat']);
        $muscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Quadriceps']);
        $exercise->muscleGroups()->attach($muscleGroup->id);

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
        $workout = Workout::factory()->for($otherUser)->create(['goal' => 'Other']);

        $response = $this->actingAs($user)->get(route('workouts.edit', $workout));

        $response->assertRedirect();
    });
});

describe('tests for the "update" method of WorkoutController', function () {
    it('should update workout and synchronize nested sections and exercises', function () {
        $user = User::factory()->create();
        $exerciseA = Exercise::factory()->for($user)->create(['name' => 'Bench Press']);
        $exerciseB = Exercise::factory()->for($user)->create(['name' => 'Squat']);
        $muscleGroup = MuscleGroup::factory()->for($user)->create(['name' => 'Pectorals']);
        $exerciseA->muscleGroups()->attach($muscleGroup->id);
        $exerciseB->muscleGroups()->attach($muscleGroup->id);

        $workout = Workout::factory()->for($user)->create([
            'goal' => 'Initial goal',
            'method' => 'Initial method',
            'is_active' => true,
        ]);

        $section = WorkoutSection::factory()->for($workout)->create([
            'name' => 'Initial section',
            'order' => 1,
        ]);

        $workoutExercise = WorkoutExercise::factory()->for($section, 'section')->create([
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

        $response->assertRedirect(route('workouts.show', Workout::query()->whereBelongsTo($user)->sole()));

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
        $workout = Workout::factory()->for($otherUser)->create(['goal' => 'Other']);

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
        $exercise = Exercise::factory()->for($user)->create(['name' => 'Deadlift']);

        $workout = Workout::factory()->for($user)->create(['goal' => 'Delete me']);
        $section = WorkoutSection::factory()->for($workout)->create(['name' => 'Section', 'order' => 1]);
        $exerciseRow = WorkoutExercise::factory()->for($section, 'section')->create([
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
        $workout = Workout::factory()->for($otherUser)->create(['goal' => 'Other']);

        $response = $this->actingAs($user)->delete(route('workouts.destroy', $workout));

        $response->assertRedirect();
        $this->assertDatabaseHas('workouts', ['id' => $workout->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('workouts.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});

it('searches workout goals methods sections and exercises without exposing other users plans', function (string $search) {
    $row = WorkoutExercise::factory()->create();
    $workout = $row->section->workout;
    $workout->update(['goal' => 'Strength plan', 'method' => 'Full body']);
    $row->section->update(['name' => 'Monday session']);
    $row->exercise->update(['name' => 'Bench press']);
    Workout::factory()->for($workout->user)->create(['goal' => 'Unrelated', 'method' => 'Other']);
    Workout::factory()->create(['goal' => $search]);

    $this->actingAs($workout->user)->get(route('workouts.index', ['search' => $search]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('workouts.data', 1)
            ->where('workouts.data.0.id', $workout->id)
            ->where('workouts.data.0.sections_count', 1)
            ->where('workouts.data.0.exercises_count', 1)
            ->missing('workouts.data.0.sections')
        );
})->with(['Strength', 'Full body', 'Monday', 'Bench press']);

it('filters workout status and paginates sorted results while retaining the query', function () {
    $user = User::factory()->create();
    Workout::factory()->for($user)->create(['goal' => 'A', 'is_active' => true]);
    $second = Workout::factory()->for($user)->create(['goal' => 'B', 'is_active' => true]);
    Workout::factory()->for($user)->create(['goal' => 'C', 'is_active' => false]);
    Workout::factory()->create(['goal' => 'D', 'is_active' => true]);

    $this->actingAs($user)->get(route('workouts.index', ['filters' => 'is_active:1', 'sort_by' => 'goal', 'sort_dir' => 'asc', 'per_page' => 1, 'page' => 2]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('workouts.total', 2)
            ->where('workouts.current_page', 2)
            ->has('workouts.data', 1)
            ->where('workouts.data.0.id', $second->id)
            ->where('workouts.prev_page_url', fn ($url) => str_contains($url, 'sort_by=goal') && str_contains($url, 'filters=is_active%3A1'))
        );
});

it('finds inactive plans and returns an empty result for an unmatched search', function () {
    $user = User::factory()->create();
    $inactive = Workout::factory()->for($user)->create(['is_active' => false, 'goal' => 'Archived plan']);
    Workout::factory()->for($user)->create(['is_active' => true]);

    $this->actingAs($user)->get(route('workouts.index', ['filters' => 'is_active:0']))
        ->assertInertia(fn (AssertableInertia $page) => $page->has('workouts.data', 1)->where('workouts.data.0.id', $inactive->id));
    $this->get(route('workouts.index', ['search' => 'No matching plan']))
        ->assertInertia(fn (AssertableInertia $page) => $page->has('workouts.data', 0));
});

it('falls back to a permitted sort column', function () {
    $user = User::factory()->create();
    Workout::factory()->for($user)->create();
    $latest = Workout::factory()->for($user)->create();

    $this->actingAs($user)->get(route('workouts.index', ['sort_by' => 'user_id']))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('workouts.data.0.id', $latest->id));
});

it('includes catalog modal translations when building or editing a workout', function (string $action) {
    $workout = Workout::factory()->create();

    $this->actingAs($workout->user)->get(route('workouts.'.$action, $action === 'edit' ? $workout : []))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('lang.workout_pages.form')
            ->has('lang.exercise_pages.form.create_muscle_group')
            ->has('lang.muscle_group_pages.form.name')
        );
})->with(['create', 'edit']);

it('shows the complete prescription in section and exercise order', function () {
    $workout = Workout::factory()->create(['filled_at' => '2026-08-10', 'rest_between_sets' => 60]);
    $last = WorkoutSection::factory()->for($workout)->create(['name' => 'B', 'order' => 2]);
    $first = WorkoutSection::factory()->for($workout)->create(['name' => 'A', 'order' => 1]);
    $exercise = Exercise::factory()->for($workout->user)->create(['name' => 'Squat']);
    WorkoutExercise::factory()->for($first, 'section')->for($exercise)->create(['order' => 2]);
    $row = WorkoutExercise::factory()->for($first, 'section')->for($exercise)->create(['order' => 1, 'sets' => 4, 'reps' => '10', 'load' => 0, 'rest_seconds' => null, 'notes' => 'Controlled tempo']);

    $this->actingAs($workout->user)->get(route('workouts.show', $workout))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('workout.sections.0.id', $first->id)
            ->where('workout.sections.1.id', $last->id)
            ->where('workout.sections.0.exercises.0.id', $row->id)
            ->where('workout.sections.0.exercises.0.exercise.name', 'Squat')
            ->where('workout.sections.0.exercises.0.sets', 4)
            ->where('workout.sections.0.exercises.0.reps', '10')
            ->where('workout.sections.0.exercises.0.notes', 'Controlled tempo')
            ->where('workout.sections.0.exercises.0.rest_seconds', null)
            ->where('workout.rest_between_sets', 60)
        );
});
