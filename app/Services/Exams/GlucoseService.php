<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\GlucoseServiceInterface;
use App\Models\Exams\Glucose;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GlucoseService implements GlucoseServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{0: LengthAwarePaginator<int, Glucose>, 1: array<string, mixed>}
     */
    public function getGlucosePageAndChartData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $glucoses = Glucose::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->whereLike('glucose_level', "%{$search}%")
                    ->orWhereLike('glycated_hemoglobin', "%{$search}%")
                    ->orWhereLike('estimated_average_glucose', "%{$search}%");
            }))
            ->orderBy($sortBy ?? 'created_at', $sortDir === 'desc' ? 'desc' : 'asc')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     glucose_level: float|int|null,
         *     glycated_hemoglobin: float|int|null,
         *     estimated_average_glucose: float|int|null
         * }> $chartRows
         */
        $chartRows = Glucose::query()
            ->selectRaw('DATE(report_date) as label, AVG(glucose_level) as glucose_level, AVG(glycated_hemoglobin) as glycated_hemoglobin, AVG(estimated_average_glucose) as estimated_average_glucose')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'glucose_level' => ['label' => __('glucose_pages.index.table.columns.glucose_level'), 'data' => $row->glucose_level],
                'glycated_hemoglobin' => ['label' => __('glucose_pages.index.table.columns.glycated_hemoglobin'), 'data' => $row->glycated_hemoglobin],
                'estimated_average_glucose' => ['label' => __('glucose_pages.index.table.columns.estimated_average_glucose'), 'data' => $row->estimated_average_glucose],
            ],
        ])->toArray();

        return [$glucoses, $chartData];
    }
}
