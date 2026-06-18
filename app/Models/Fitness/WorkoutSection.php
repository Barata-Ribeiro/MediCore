<?php

namespace App\Models\Fitness;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name The name of the workout section, e.g. "Superior", "Inferior", "Day A", etc.
 * @property int $order The order of the workout section in the workout
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $workout_id
 * @property-read Collection<int, WorkoutExercise> $exercises
 * @property-read int|null $exercises_count
 * @property-read bool|null $exercises_exists
 * @property-read Workout $workout
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereWorkoutId($value)
 *
 * @mixin \Eloquent
 */
#[Table('workout_sections')]
#[Fillable(['name', 'order', 'workout_id'])]
class WorkoutSection extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = ['order' => 0];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['order' => 'integer'];
    }

    /**
     * Get the workout that owns the workout section.
     *
     * @return BelongsTo<Workout, $this>
     */
    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    /**
     * Get the exercises for the workout section.
     *
     * @return HasMany<WorkoutExercise, $this>
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class)->orderBy(['order']);
    }
}
