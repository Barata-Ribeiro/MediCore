<?php

namespace App\Models\Fitness;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('muscle_groups')]
#[Fillable(['name'])]
class MuscleGroup extends Model
{
    /**
     * Get the exercises that belong to the muscle group.
     *
     * @return BelongsToMany<Exercise, MuscleGroup, TPivotModel>
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_muscle_groups');
    }

    /**
     * Get the workout exercises that belong to the muscle group.
     *
     * @return HasMany<WorkoutExercise, MuscleGroup>
     */
    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
