<?php

namespace App\Services\Fitness;

use App\Common\Helpers;
use App\Interfaces\Fitness\ExerciseServiceInterface;
use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use Eloquent;
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
     */
    public function getExercisesData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, $filters): LengthAwarePaginator
    {
        $isSql = $this->isSqlDriver;

        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

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
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, function (Eloquent $qr) use ($search, $isSql) {
                if ($isSql) {
                    $booleanQuery = Helpers::buildBooleanQuery($search);
                    $qr->whereFullText(['exercises.name', 'exercises.description', 'exercises.video_url'], $booleanQuery)
                        ->orWhereHas('muscleGroups', fn (Builder $muscleGroups) => $muscleGroups->whereLike('muscle_groups.name', "%{$search}%"));
                } else {
                    $qr->where(function (Builder $query) use ($search) {
                        $query->whereLike('exercises.name', "%{$search}%")
                            ->orWhereLike('exercises.description', "%{$search}%")
                            ->orWhereLike('exercises.video_url', "%{$search}%")
                            ->orWhereHas('muscleGroups', fn (Builder $muscleGroups) => $muscleGroups->whereLike('muscle_groups.name', "%{$search}%"));
                    });
                }
            })->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();
    }
}
