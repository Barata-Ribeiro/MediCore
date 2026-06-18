<?php

namespace App\Models\Fitness;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $workout_section_id
 * @property int $exercise_id
 * @property int|null $muscle_group_id
 * @property string|null $code Code of the exercise, e.g., "A1", "B2"
 * @property int $order The order of the exercise in the workout section
 * @property int $sets Number of sets
 * @property int $reps Reps, e.g., "8-12", "AMRAP", "Failure", etc.
 * @property float|null $load Load, default unit is kg
 * @property string $load_unit Unit of the load, e.g., "kg", "lbs", "bodyweight", etc.
 * @property int|null $rest_seconds Rest time in seconds between sets
 * @property string|null $notes Additional notes for the exercise, e.g., "Use a spotter", "Focus on form", etc.
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Exercise $exercise
 * @property-read MuscleGroup|null $muscleGroup
 * @property-read WorkoutSection $section
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereExerciseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereLoad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereLoadUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereMuscleGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereReps($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereRestSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereSets($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereWorkoutSectionId($value)
 *
 * @mixin \Eloquent
 */
#[Table('workout_exercises')]
#[Fillable(['workout_section_id', 'exercise_id', 'muscle_group_id', 'code', 'order', 'sets', 'reps', 'load', 'load_unit', 'rest_seconds', 'notes'])]
class WorkoutExercise extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'workout_section_id' => 'integer',
            'exercise_id' => 'integer',
            'muscle_group_id' => 'integer',
            'order' => 'integer',
            'sets' => 'integer',
            'reps' => 'integer',
            'load' => 'float',
            'rest_seconds' => 'integer',
        ];
    }

    /**
     * Get the workout section that owns the exercise.
     *
     * @return BelongsTo<WorkoutSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(WorkoutSection::class, 'workout_section_id');
    }

    /**
     * Get the exercise that owns the workout exercise.
     *
     * @return BelongsTo<Exercise, $this>
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Get the muscle group that owns the workout exercise.
     *
     * @return BelongsTo<MuscleGroup, $this>
     */
    public function muscleGroup(): BelongsTo
    {
        return $this->belongsTo(MuscleGroup::class);
    }
}
