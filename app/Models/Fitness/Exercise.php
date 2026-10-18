<?php

namespace App\Models\Fitness;

use App\Models\User;
use Database\Factories\Fitness\ExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperExercise
 */
#[Table('exercises')]
#[Fillable(['name', 'description', 'video_url', 'user_id'])]
class Exercise extends Model
{
    /** @use HasFactory<ExerciseFactory> */
    use HasFactory;

    /**
     * Get the user that owns the exercise.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the muscle groups that belong to the exercise.
     *
     * @return BelongsToMany<MuscleGroup, $this>
     */
    public function muscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_groups');
    }

    /**
     * Get the workout exercises that belong to the exercise.
     *
     * @return HasMany<WorkoutExercise, $this>
     */
    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
