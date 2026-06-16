<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\Glucose;
use Illuminate\Pagination\LengthAwarePaginator;

interface GlucoseServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, Glucose>,
     *     1: array<string, mixed>
     * }
     */
    public function getGlucosePageAndChartData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
