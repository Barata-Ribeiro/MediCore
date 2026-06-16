<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\VitaminB12;
use Illuminate\Pagination\LengthAwarePaginator;

interface VitaminB12ServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, VitaminB12>,
     *     1: array<string, mixed>
     * }
     */
    public function getVitaminB12sData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
