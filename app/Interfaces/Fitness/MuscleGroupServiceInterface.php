<?php

namespace App\Interfaces\Fitness;

use Illuminate\Pagination\LengthAwarePaginator;

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
     * @param  string|null  $sortBy  Column or attribute name to sort by.
     * @param  string|null  $sortDir  Sort direction ('asc'|'desc'); when null the service default is used.
     * @param  string|null  $search  Search term to be applied to relevant muscle group fields (name, description, etc.).
     * @param  string|array|null  $filters  Additional filters to apply (e.g. associative array of field => value, filter objects, or a query callback).
     * @return LengthAwarePaginator Paginated collection of muscle group models.
     */
    public function getMuscleGroupsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, $filters): LengthAwarePaginator;
}
