<?php

namespace App\Interfaces\Fitness;

use App\Models\Fitness\MuscleGroup;
use App\Services\Fitness\MuscleGroupService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

#[Bind(MuscleGroupService::class)]
interface MuscleGroupServiceInterface
{
    /**
     * Retrieve a paginated list of muscle groups with optional sorting, searching, and filtering.
     *
     * This method returns a LengthAwarePaginator of muscle groups, applying the supplied
     * pagination size, sort column/direction, global search string, and any additional
     * filtering criteria. Any null arguments should cause the implementation to fall
     * back to sensible defaults (e.g. default per-page size or sort order).
     *
     * @param  int|null  $perPage  Number of items per page; when null the service default is used.
     * @param  string|null  $search  Search term to be applied to relevant muscle group fields (name, description, etc.).
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters  Additional filters to apply.
     * @return LengthAwarePaginator<int, MuscleGroup> Paginated collection of muscle group models.
     */
    public function getMuscleGroupsData(?int $perPage, array $sorting, ?string $search, ?array $filters): LengthAwarePaginator;
}
