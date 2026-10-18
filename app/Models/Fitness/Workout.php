<?php

namespace App\Models\Fitness;

use App\Models\User;
use Database\Factories\Fitness\WorkoutFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperWorkout
 */
#[Table('workouts')]
#[Fillable(['filled_at', 'next_change_at', 'goal', 'method', 'rest_between_sets', 'rest_between_exercises', 'is_active', 'user_id'])]
class Workout extends Model
{
    /** @use HasFactory<WorkoutFactory> */
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = ['is_active' => true];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'filled_at' => 'immutable_date',
            'next_change_at' => 'immutable_date',
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
