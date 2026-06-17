<?php

namespace App\Models\Fitness;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('exercises')]
#[Fillable(['name', 'description', 'video_url'])]
class Exercise extends Model
{
    /**
     * Get the muscle groups that belong to the exercise.
     *
     * @return BelongsToMany<MuscleGroup, Exercise, TPivotModel>
     */
    public function muscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_groups');
    }

    /**
     * Get the workout exercises that belong to the exercise.
     *
     * @return HasMany<WorkoutExercise, Exercise>
     */
    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
