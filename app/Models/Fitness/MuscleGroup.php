<?php

namespace App\Models\Fitness;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name The name of the muscle group, e.g. "Pectorals", "Quadriceps", etc.
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Exercise> $exercises
 * @property-read int|null $exercises_count
 * @property-read bool|null $exercises_exists
 * @property-read Collection<int, WorkoutExercise> $workoutExercises
 * @property-read int|null $workout_exercises_count
 * @property-read bool|null $workout_exercises_exists
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Table('muscle_groups')]
#[Fillable(['name'])]
class MuscleGroup extends Model
{
    /**
     * Get the exercises that belong to the muscle group.
     *
     * @return BelongsToMany<Exercise, $this, TPivotModel>
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
