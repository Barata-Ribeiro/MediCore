<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\UltrasensitiveTshServiceInterface;
use App\Models\Exams\UltrasensitiveTsh;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UltrasensitiveTshService implements UltrasensitiveTshServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{0: LengthAwarePaginator<int, UltrasensitiveTsh>, 1: array<string, mixed>}
     */
    public function getUltrasensitiveTshsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $ultrasensitiveTshs = UltrasensitiveTsh::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('tsh_level', "%{$search}%"))
            ->orderBy($sortBy ?? 'created_at', $sortDir === 'desc' ? 'desc' : 'asc')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     tsh_level: float|int|null
         * }> $chartRows
         */
        $chartRows = UltrasensitiveTsh::query()
            ->selectRaw('DATE(report_date) as label, AVG(tsh_level) as tsh_level')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'tsh_level' => ['label' => __('ultrasensitive_tsh_pages.index.table.columns.tsh_level'), 'data' => $row->tsh_level],
            ],
        ])->toArray();

        return [$ultrasensitiveTshs, $chartData];
    }
}
