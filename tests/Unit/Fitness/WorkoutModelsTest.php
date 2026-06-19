<?php

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use App\Models\Fitness\WorkoutExercise;
use App\Models\Fitness\WorkoutSection;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('fitness model relationships are wired to expected foreign and pivot tables', function () {
    $exerciseMuscleGroups = (new Exercise)->muscleGroups();
    $muscleGroupExercises = (new MuscleGroup)->exercises();
    $workoutSections = (new Workout)->sections();
    $sectionExercises = (new WorkoutSection)->exercises();
    $exerciseSection = (new WorkoutExercise)->section();
    $exerciseOwner = (new WorkoutExercise)->exercise();
    $muscleGroupOwner = (new WorkoutExercise)->muscleGroup();

    expect($exerciseMuscleGroups)
        ->toBeInstanceOf(BelongsToMany::class)
        ->and($exerciseMuscleGroups->getTable())->toBe('exercise_muscle_groups');

    expect($muscleGroupExercises)
        ->toBeInstanceOf(BelongsToMany::class)
        ->and($muscleGroupExercises->getTable())->toBe('exercise_muscle_groups');

    expect($workoutSections)
        ->toBeInstanceOf(HasMany::class)
        ->and($workoutSections->getForeignKeyName())->toBe('workout_id');

    expect($sectionExercises)
        ->toBeInstanceOf(HasMany::class)
        ->and($sectionExercises->getForeignKeyName())->toBe('workout_section_id');

    expect($exerciseSection)
        ->toBeInstanceOf(BelongsTo::class)
        ->and($exerciseSection->getForeignKeyName())->toBe('workout_section_id');

    expect($exerciseOwner)
        ->toBeInstanceOf(BelongsTo::class)
        ->and($exerciseOwner->getForeignKeyName())->toBe('exercise_id');

    expect($muscleGroupOwner)
        ->toBeInstanceOf(BelongsTo::class)
        ->and($muscleGroupOwner->getForeignKeyName())->toBe('muscle_group_id');
});

test('workout models mirror migration defaults', function () {
    $workout = new Workout;
    $section = new WorkoutSection;
    $workoutExercise = new WorkoutExercise;

    expect($workout->is_active)->toBeTrue();
    expect($section->order)->toBe(0);
    expect($workoutExercise->order)->toBe(0);
    expect($workoutExercise->sets)->toBe(3);
    expect($workoutExercise->reps)->toBe('8-12');
    expect($workoutExercise->load_unit)->toBe('kg');
});

test('workout date fields cast to CarbonImmutable and reps remains string', function () {
    $workout = new Workout([
        'filled_at' => '2026-06-18',
        'next_change_at' => '2026-07-02',
    ]);

    $workoutExercise = new WorkoutExercise([
        'reps' => 'AMRAP',
    ]);

    expect($workout->filled_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($workout->next_change_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($workoutExercise->reps)->toBeString()->toBe('AMRAP');
});
