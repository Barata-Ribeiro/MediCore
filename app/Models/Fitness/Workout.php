<?php

namespace App\Models\Fitness;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property CarbonImmutable|null $filled_at The date when the workout was filled out
 * @property CarbonImmutable|null $next_change_at The date when the workout should be changed next
 * @property string|null $goal The goal of the workout, e.g. "lose weight", "build muscle", etc.
 * @property string|null $method The method of the workout, e.g. "A/B split", "full body", etc.
 * @property int|null $rest_between_sets The rest time between sets in seconds
 * @property int|null $rest_between_exercises The rest time between exercises in seconds
 * @property bool $is_active Whether the workout is active or not
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $user_id
 * @property-read Collection<int, WorkoutSection> $sections
 * @property-read int|null $sections_count
 * @property-read bool|null $sections_exists
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereFilledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereNextChangeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereRestBetweenExercises($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereRestBetweenSets($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[Table('workouts')]
#[Fillable(['filled_at', 'next_change_at', 'goal', 'method', 'rest_between_sets', 'rest_between_exercises', 'is_active', 'user_id'])]
class Workout extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'filled_at' => 'date',
            'next_change_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<WorkoutSection, $this>
     */
    public function sections(): HasMany
    {
        return $this->hasMany(WorkoutSection::class)->orderBy('order');
    }
}
