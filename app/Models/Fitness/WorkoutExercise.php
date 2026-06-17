<?php

namespace App\Models\Fitness;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @return BelongsTo<WorkoutSection, WorkoutExercise>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(WorkoutSection::class, 'workout_section_id');
    }

    /**
     * Get the exercise that owns the workout exercise.
     *
     * @return BelongsTo<Exercise, WorkoutExercise>
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Get the muscle group that owns the workout exercise.
     *
     * @return BelongsTo<MuscleGroup, WorkoutExercise>
     */
    public function muscleGroup(): BelongsTo
    {
        return $this->belongsTo(MuscleGroup::class);
    }
}
