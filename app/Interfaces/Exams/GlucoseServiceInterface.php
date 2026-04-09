<?php

namespace App\Interfaces\Exams;

use Illuminate\Pagination\LengthAwarePaginator;

interface GlucoseServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @return array{
     *     0: LengthAwarePaginator,
     *     1: array<string, mixed>
     * }
     */
    public function getGlucosePageAndChartData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
