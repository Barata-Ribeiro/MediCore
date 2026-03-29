<?php

namespace App\Interfaces\Exams;

interface LipidProfileServiceInterface
{
    public function getLipidProfileData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
