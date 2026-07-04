<?php

namespace App\Services\Exams;

use App\Interfaces\Exams\TotalProteinsAndFractionsServiceInterface;

class TotalProteinsAndFractionsService implements TotalProteinsAndFractionsServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getTotalProteinsAndFractionsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array {}
}
