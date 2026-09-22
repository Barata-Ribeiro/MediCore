<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\TotalProteinsAndFractions;
use App\Services\Exams\TotalProteinsAndFractionsService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Pagination\LengthAwarePaginator;

#[Bind(TotalProteinsAndFractionsService::class)]
interface TotalProteinsAndFractionsServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, TotalProteinsAndFractions>,
     *     1: array<string, mixed>
     * }
     */
    public function getTotalProteinsAndFractionsData(?int $perPage, array $sorting, ?string $search, ?array $filters): array;
}
