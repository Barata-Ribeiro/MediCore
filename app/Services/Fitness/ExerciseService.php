<?php

namespace App\Services\Fitness;

use App\Common\Helpers;
use App\Interfaces\Fitness\ExerciseServiceInterface;
use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use function in_array;

class ExerciseService implements ExerciseServiceInterface
{
    private bool $isSqlDriver;

    public function __construct()
    {
        $this->isSqlDriver = in_array(DB::getDriverName(), ['mysql', 'pgsql']);
    }

    /**
     * {@inheritDoc}
     *
     * @param  array<string, mixed>|null  $filters
     * @return LengthAwarePaginator<int, Exercise>
     */
    public function getExercisesData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): LengthAwarePaginator
    {
        $isSql = $this->isSqlDriver;

        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);

        return Exercise::query()
            ->select('exercises.*')
            ->selectSub(
                MuscleGroup::query()
                    ->select('muscle_groups.name')
                    ->join('exercise_muscle_groups', 'exercise_muscle_groups.muscle_group_id', '=', 'muscle_groups.id')
                    ->whereColumn('exercise_muscle_groups.exercise_id', 'exercises.id')
                    ->orderBy('muscle_groups.name')
                    ->limit(1),
                'muscle_group_name',
            )
            ->whereBelongsTo(auth()->user())
            ->with(['muscleGroups' => fn ($query) => $query->select(['muscle_groups.id', 'name'])->orderBy('name')])
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($search, function (Builder $query) use ($search, $isSql) {
                if ($isSql) {
                    $booleanQuery = Helpers::buildBooleanQuery($search);
                    $query->whereFullText(['exercises.name', 'exercises.description', 'exercises.video_url'], $booleanQuery)
                        ->orWhereHas('muscleGroups', fn (Builder $muscleGroups) => $muscleGroups->whereLike('muscle_groups.name', "%{$search}%"));
                } else {
                    $query->where(function (Builder $searchQuery) use ($search) {
                        $searchQuery->whereLike('exercises.name', "%{$search}%")
                            ->orWhereLike('exercises.description', "%{$search}%")
                            ->orWhereLike('exercises.video_url', "%{$search}%")
                            ->orWhereHas('muscleGroups', fn (Builder $muscleGroups) => $muscleGroups->whereLike('muscle_groups.name', "%{$search}%"));
                    });
                }
            })->orderBy($sortBy ?? 'id', $sortDir === 'desc' ? 'desc' : 'asc')
            ->paginate($perPage ?? 10)
            ->withQueryString();
    }
}
