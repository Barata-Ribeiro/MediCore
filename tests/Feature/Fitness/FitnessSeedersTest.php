<?php

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use App\Models\Fitness\WorkoutExercise;
use App\Models\Fitness\WorkoutSection;
use App\Models\User;
use Database\Seeders\Fitness\ExerciseSeeder;
use Database\Seeders\Fitness\MuscleGroupSeeder;
use Database\Seeders\Fitness\WorkoutExerciseSeeder;
use Database\Seeders\Fitness\WorkoutSectionSeeder;
use Database\Seeders\Fitness\WorkoutSeeder;

dataset('fitness seeders', [
    'Exercise' => [Exercise::class, ExerciseSeeder::class, 'exercises', 'user'],
    'MuscleGroup' => [MuscleGroup::class, MuscleGroupSeeder::class, 'muscle_groups', 'user'],
    'Workout' => [Workout::class, WorkoutSeeder::class, 'workouts', 'user'],
    'WorkoutSection' => [WorkoutSection::class, WorkoutSectionSeeder::class, 'workout_sections', 'workout.user'],
    'WorkoutExercise' => [WorkoutExercise::class, WorkoutExerciseSeeder::class, 'workout_exercises', 'section.workout.user'],
]);

it('seeds five samples for a separate owner without changing existing records', function (string $model, string $seeder, string $table, string $ownerPath) {
    $existing = $model::factory()->create();
    $original = $existing->getRawOriginal();
    $owner = data_get($existing, $ownerPath);

    $this->seed($seeder);

    $this->assertDatabaseCount($table, 6);
    $this->assertDatabaseHas($table, $original);
    $samples = $model::query()->with($ownerPath)->whereKeyNot($existing->id)->get();
    $ownerIds = $samples->map(fn ($sample): int => data_get($sample, $ownerPath)->id)->unique();
    expect($ownerIds)->toHaveCount(1)->not->toContain($owner->id);
})->with('fitness seeders');

it('does not seed sample data outside local and testing environments', function (string $model, string $seeder, string $table, string $ownerPath, string $environment) {
    $userCount = User::query()->count();
    $this->app->instance('env', $environment);

    $this->app->make($seeder)->run();

    $this->assertDatabaseCount($table, 0);
    $this->assertDatabaseCount('users', $userCount);
})->with('fitness seeders')->with(['production', 'staging']);

it('creates an exercise from the workout owners catalog by default', function () {
    $section = WorkoutSection::factory()->create();
    $userCount = User::query()->count();

    $record = WorkoutExercise::factory()->for($section, 'section')->create();

    expect($record->exercise->user_id)->toBe($section->workout->user_id);
    $this->assertDatabaseCount('users', $userCount);
    $this->assertDatabaseCount('workouts', 1);
    $this->assertDatabaseCount('workout_sections', 1);
});

it('seeds exercise rows with a linked muscle group in the workout owners catalog', function () {
    $this->seed(WorkoutExerciseSeeder::class);

    $records = WorkoutExercise::query()->with(['section.workout', 'exercise.muscleGroups', 'muscleGroup'])->get();
    foreach ($records as $record) {
        expect($record->exercise->user_id)->toBe($record->section->workout->user_id);
        expect($record->muscleGroup->user_id)->toBe($record->section->workout->user_id);
        $this->assertDatabaseHas('exercise_muscle_groups', [
            'exercise_id' => $record->exercise_id,
            'muscle_group_id' => $record->muscle_group_id,
        ]);
    }
});
