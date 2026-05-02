<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\UricAcidServiceInterface;
use App\Models\Exams\UricAcid;
use Concurrency;

class UricAcidService implements UricAcidServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUricAcidsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $uricAcids = UricAcid::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('uric_acid', "%{$search}%"))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = UricAcid::query()
            ->selectRaw('DATE(report_date) as label, AVG(uric_acid_level) as uric_acid_level')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'uric_acid_level' => [
                    'label' => __('uric_acid_pages.index.table.columns.uric_acid_level'),
                    'data' => $row->uric_acid_level,
                ],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$uricAcids, $chartData];
        }

        return Concurrency::run([
            fn () => $uricAcids,
            fn () => $chartData,
        ]);
    }
}
