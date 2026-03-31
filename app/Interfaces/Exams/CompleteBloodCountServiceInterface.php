<?php

namespace App\Interfaces\Exams;

interface CompleteBloodCountServiceInterface
{
    public function getCompleteBloodCountData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
