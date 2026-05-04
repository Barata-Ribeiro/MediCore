<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\UreaAndCreatinineServiceInterface;
use App\Models\Exams\UreaAndCreatinine;
use Concurrency;

class UreaAndCreatinineService implements UreaAndCreatinineServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUreaAndCreatininesData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $uacs = UreaAndCreatinine::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('urea_level', "%{$search}%")->orWhereLike('creatinine_level', "%{$search}%"))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = UreaAndCreatinine::query()
            ->selectRaw('DATE(report_date) as label, AVG(urea_level) as urea_level, AVG(creatinine_level) as creatinine_level, (NULLIF(AVG(urea_level), 0) / NULLIF(AVG(creatinine_level), 0)) as urea_to_creatinine_ratio')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'urea_level' => [
                    'label' => __('urea_and_creatinine_pages.index.table.columns.urea_level'),
                    'data' => $row->urea_level,
                ],
                'creatinine_level' => [
                    'label' => __('urea_and_creatinine_pages.index.table.columns.creatinine_level'),
                    'data' => $row->creatinine_level,
                ],
                'urea_to_creatinine_ratio' => [
                    'label' => __('urea_and_creatinine_pages.index.chart.datasets.urea_to_creatinine_ratio'),
                    'data' => $row->urea_to_creatinine_ratio,
                ],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$uacs, $chartData];
        }

        return Concurrency::run([
            fn () => $uacs,
            fn () => $chartData,
        ]);
    }
}
