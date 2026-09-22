<?php

namespace App\Services\Exams;

use App\Common\DataTableQuery;
use App\Interfaces\Exams\UricAcidServiceInterface;
use App\Models\Exams\UricAcid;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UricAcidService implements UricAcidServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{0: LengthAwarePaginator<int, UricAcid>, 1: array<string, mixed>}
     */
    public function getUricAcidsData(?int $perPage, array $sorting, ?string $search, ?array $filters): array
    {

        $uricAcids = UricAcid::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($search !== '', fn ($q) => $q->whereLike('uric_acid_level', "%{$search}%"))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'uric_acid_level' => 'number',
                'report_date' => 'date',
                'created_at' => 'date',
            ]))
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     uric_acid_level: float|int|null
         * }> $chartRows
         */
        $chartRows = UricAcid::query()
            ->selectRaw('DATE(report_date) as label, AVG(uric_acid_level) as uric_acid_level')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'uric_acid_level' => [
                    'label' => __('uric_acid_pages.index.table.columns.uric_acid_level'),
                    'data' => $row->uric_acid_level,
                ],
            ],
        ])->toArray();

        return [$uricAcids, $chartData];
    }
}
