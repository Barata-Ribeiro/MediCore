<?php

namespace App\Interfaces\Exams;

use App\Models\Exams\TgoAndTgp;
use App\Services\Exams\TgoAndTgpService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Pagination\LengthAwarePaginator;

#[Bind(TgoAndTgpService::class)]
interface TgoAndTgpServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{
     *     0: LengthAwarePaginator<int, TgoAndTgp>,
     *     1: array<string, mixed>
     * }
     */
    public function getTgoAndTgpsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
