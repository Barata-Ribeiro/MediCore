<?php

namespace App\Interfaces\Exams;

interface GlucoseServiceInterface
{
    public function getGlucosePageAndChartData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
