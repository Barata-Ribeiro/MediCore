<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\CompleteBloodCount;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompleteBloodCountServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, CompleteBloodCount>,
     *     1: array<string, mixed>
     * }
     */
    public function getCompleteBloodCountData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
