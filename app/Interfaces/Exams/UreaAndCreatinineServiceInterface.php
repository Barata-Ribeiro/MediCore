<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\UreaAndCreatinine;
use App\Services\Exams\UreaAndCreatinineService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Pagination\LengthAwarePaginator;

#[Bind(UreaAndCreatinineService::class)]
interface UreaAndCreatinineServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, UreaAndCreatinine>,
     *     1: array<string, mixed>
     * }
     */
    public function getUreaAndCreatininesData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
