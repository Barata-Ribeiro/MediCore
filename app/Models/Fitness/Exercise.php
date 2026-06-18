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
 * @property string $name The name of the exercise, e.g. "Bench Press", "Squat", etc.
 * @property string|null $description A detailed description of how to perform the exercise, including proper form and technique.
 * @property string|null $video_url A URL to a video demonstrating the exercise, if available.
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, MuscleGroup> $muscleGroups
 * @property-read int|null $muscle_groups_count
 * @property-read bool|null $muscle_groups_exists
 * @property-read Collection<int, WorkoutExercise> $workoutExercises
 * @property-read int|null $workout_exercises_count
 * @property-read bool|null $workout_exercises_exists
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereVideoUrl($value)
 *
 * @mixin \Eloquent
 */
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
