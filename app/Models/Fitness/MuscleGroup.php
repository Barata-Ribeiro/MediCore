<?php

namespace App\Models\Fitness;

use App\Models\User;
use Database\Factories\Fitness\MuscleGroupFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperMuscleGroup
 */
#[Table('muscle_groups')]
#[Fillable(['name', 'user_id'])]
class MuscleGroup extends Model
{
    /** @use HasFactory<MuscleGroupFactory> */
    use HasFactory;

    /**
     * Get the user that owns the muscle group.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exercises that belong to the muscle group.
     *
     * @return BelongsToMany<Exercise, $this>
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_muscle_groups');
    }

    /**
     * Get the workout exercises that belong to the muscle group.
     *
     * @return HasMany<WorkoutExercise, $this>
     */
    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
