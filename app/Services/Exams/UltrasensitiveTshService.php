<?php

namespace App\Services\Exams;

use App\Common\DataTableQuery;
use App\Interfaces\Exams\UltrasensitiveTshServiceInterface;
use App\Models\Exams\UltrasensitiveTsh;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UltrasensitiveTshService implements UltrasensitiveTshServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{0: LengthAwarePaginator<int, UltrasensitiveTsh>, 1: array<string, mixed>}
     */
    public function getUltrasensitiveTshsData(?int $perPage, array $sorting, ?string $search, ?array $filters): array
    {

        $ultrasensitiveTshs = UltrasensitiveTsh::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($search, fn ($q) => $q->whereLike('tsh_level', "%{$search}%"))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'tsh_level' => 'number',
                'report_date' => 'date',
                'created_at' => 'date',
            ]))
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
