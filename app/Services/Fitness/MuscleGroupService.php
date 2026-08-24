<?php

namespace App\Services\Fitness;

use App\Common\Helpers;
use App\Interfaces\Fitness\MuscleGroupServiceInterface;
use App\Models\Fitness\MuscleGroup;
use Illuminate\Pagination\LengthAwarePaginator;

class MuscleGroupService implements MuscleGroupServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getMuscleGroupsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, $filters): LengthAwarePaginator
    {
        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];
        $exercisesCountRange = $filters['exercises_count'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);

        return MuscleGroup::query()
            ->whereBelongsTo(auth()->user())
            ->withCount('exercises')
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($exercisesCountRange, fn ($q) => $q
                ->has('exercises', '>=', $exercisesCountRange[0] ?? 0)
                ->has('exercises', '<=', $exercisesCountRange[1] ?? PHP_INT_MAX))
            ->when($search, fn ($q) => $q->whereLike('name', "%{$search}%"))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();
    }
}
