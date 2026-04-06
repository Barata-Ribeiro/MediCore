<?php

namespace App\Interfaces\Exams;

interface VitaminD3ServiceInterface
{
    public function getVitaminD3sData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array;
}
