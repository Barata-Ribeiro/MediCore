<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\TgoAndTgpServiceInterface;
use App\Models\Exams\TgoAndTgp;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TgoAndTgpService implements TgoAndTgpServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{0: LengthAwarePaginator<int, TgoAndTgp>, 1: array<string, mixed>}
     */
    public function getTgoAndTgpsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $records = TgoAndTgp::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [substr($reportDateStart, 0, 10), substr($reportDateEnd, 0, 10)]))
            ->when($search !== '', fn ($q) => $q->where(fn ($query) => $query
                ->whereLike('tgo_level', "%{$search}%")
                ->orWhereLike('tgp_level', "%{$search}%")))
            ->orderBy($sortBy ?? 'created_at', $sortDir === 'desc' ? 'desc' : 'asc')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     tgo_level: float|int|null,
         *     tgp_level: float|int|null,
         * }> $chartRows
         */
        $chartRows = TgoAndTgp::query()
            ->selectRaw('DATE(report_date) as label, AVG(tgo_level) as tgo_level, AVG(tgp_level) as tgp_level')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderByDesc('label')
            ->limit(5)
            ->get()
            ->reverse()
            ->values();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'tgo_level' => [
                    'label' => __('tgo_and_tgp_pages.index.table.columns.tgo_level'),
                    'data' => $row->tgo_level,
                ],
                'tgp_level' => [
                    'label' => __('tgo_and_tgp_pages.index.table.columns.tgp_level'),
                    'data' => $row->tgp_level,
                ],
            ],
        ])->toArray();

        return [$records, $chartData];
    }
}
