<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\VitaminD3;
use App\Services\Exams\VitaminD3Service;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Pagination\LengthAwarePaginator;

#[Bind(VitaminD3Service::class)]
interface VitaminD3ServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, VitaminD3>,
     *     1: array<string, mixed>
     * }
     */
    public function getVitaminD3sData(?int $perPage, array $sorting, ?string $search, ?array $filters): array;
}
