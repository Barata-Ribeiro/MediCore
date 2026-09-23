<?php

namespace App\Services\Exams;

use App\Common\DataTableQuery;
use App\Interfaces\Exams\UreaAndCreatinineServiceInterface;
use App\Models\Exams\UreaAndCreatinine;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UreaAndCreatinineService implements UreaAndCreatinineServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{0: LengthAwarePaginator<int, UreaAndCreatinine>, 1: array<string, mixed>}
     */
    public function getUreaAndCreatininesData(?int $perPage, array $sorting, ?string $search, ?array $filters): array
    {

        $uacs = UreaAndCreatinine::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($search !== '', fn ($q) => $q->where(fn ($query) => $query
                ->whereLike('urea_level', "%{$search}%")
                ->orWhereLike('creatinine_level', "%{$search}%")))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'urea_level' => 'number',
                'creatinine_level' => 'number',
                'report_date' => 'date',
                'created_at' => 'date',
            ]))
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     urea_level: float|int|null,
         *     creatinine_level: float|int|null,
         *     urea_to_creatinine_ratio: float|int|null
         * }> $chartRows
         */
        $chartRows = UreaAndCreatinine::query()
            ->selectRaw('DATE(report_date) as label, AVG(urea_level) as urea_level, AVG(creatinine_level) as creatinine_level, (NULLIF(AVG(urea_level), 0) / NULLIF(AVG(creatinine_level), 0)) as urea_to_creatinine_ratio')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
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

        return [$uacs, $chartData];
    }
}
