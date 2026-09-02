<?php

namespace App\Interfaces\Fitness;

use App\Models\Fitness\Exercise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ExerciseServiceInterface
{
    /**
     * Retrieve a paginated list of exercises with optional sorting, searching, and filtering.
     *
     * This method returns a LengthAwarePaginator of exercises, applying the supplied
     * pagination size, sort column/direction, global search string, and any additional
     * filtering criteria. Any null arguments should cause the implementation to fall
     * back to sensible defaults (e.g. default per-page size or sort order).
     *
     * @param  int|null  $perPage  Number of items per page; when null the service default is used.
     * @param  string|null  $sortBy  Column or attribute name to sort by.
     * @param  string|null  $sortDir  Sort direction ('asc'|'desc'); when null the service default is used.
     * @param  string|null  $search  Search term to be applied to relevant exercise fields (name, description, etc.).
     * @param  array<string, mixed>|null  $filters  Additional filters to apply.
     * @return LengthAwarePaginator<int, Exercise> Paginated collection of exercise models.
     */
    public function getExercisesData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): LengthAwarePaginator;
}
