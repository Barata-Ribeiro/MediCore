<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\VitaminB12ServiceInterface;
use App\Models\Exams\VitaminB12;
use Concurrency;

class VitaminB12Service implements VitaminB12ServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getVitaminB12sData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $vitaminB12s = VitaminB12::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('vitamin_b12_level', "%{$search}%"))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = VitaminB12::query()
            ->selectRaw('DATE(report_date) as label, AVG(vitamin_b12_level) as vitamin_b12_level')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'vitamin_b12_level' => ['label' => 'Vitamin B12 Level', 'data' => $row->vitamin_b12_level],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$vitaminB12s, $chartData];
        }

        return Concurrency::run([
            fn () => $vitaminB12s,
            fn () => $chartData,
        ]);
    }
}
