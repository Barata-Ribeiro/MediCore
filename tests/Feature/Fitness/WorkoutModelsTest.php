<?php

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use App\Models\Fitness\WorkoutExercise;
use App\Models\Fitness\WorkoutSection;
use Carbon\CarbonImmutable;

test('fitness relationships retrieve the linked catalog and ordered workout records', function () {
    $muscleGroup = MuscleGroup::factory()->create();
    $exercise = Exercise::factory()->for($muscleGroup->user)->hasAttached($muscleGroup)->create();
    $workout = Workout::factory()->for($muscleGroup->user)->create();
    $laterSection = WorkoutSection::factory()->for($workout)->create(['order' => 2]);
    $firstSection = WorkoutSection::factory()->for($workout)->create(['order' => 1]);
    $laterRow = WorkoutExercise::factory()->for($firstSection, 'section')->for($exercise)->for($muscleGroup)->create(['order' => 2]);
    $firstRow = WorkoutExercise::factory()->for($firstSection, 'section')->for($exercise)->for($muscleGroup)->create(['order' => 1]);

    expect($exercise->fresh()->muscleGroups->modelKeys())->toBe([$muscleGroup->id]);
    expect($muscleGroup->fresh()->exercises->modelKeys())->toBe([$exercise->id]);
    expect($workout->fresh()->sections->modelKeys())->toBe([$firstSection->id, $laterSection->id]);
    expect($firstSection->fresh()->exercises->modelKeys())->toBe([$firstRow->id, $laterRow->id]);
    expect($firstRow->fresh()->section->id)->toBe($firstSection->id);
    expect($firstRow->fresh()->exercise->id)->toBe($exercise->id);
    expect($firstRow->fresh()->muscleGroup->id)->toBe($muscleGroup->id);
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
