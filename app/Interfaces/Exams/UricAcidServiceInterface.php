<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\UricAcid;
use App\Services\Exams\UricAcidService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Pagination\LengthAwarePaginator;

#[Bind(UricAcidService::class)]
interface UricAcidServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, UricAcid>,
     *     1: array<string, mixed>
     * }
     */
    public function getUricAcidsData(?int $perPage, array $sorting, ?string $search, ?array $filters): array;
}
