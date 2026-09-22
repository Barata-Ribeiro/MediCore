<?php

namespace App\Services\Exams;

use App\Common\DataTableQuery;
use App\Interfaces\Exams\VitaminB12ServiceInterface;
use App\Models\Exams\VitaminB12;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VitaminB12Service implements VitaminB12ServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{0: LengthAwarePaginator<int, VitaminB12>, 1: array<string, mixed>}
     */
    public function getVitaminB12sData(?int $perPage, array $sorting, ?string $search, ?array $filters): array
    {

        $vitaminB12s = VitaminB12::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($search, fn ($q) => $q->whereLike('vitamin_b12_level', "%{$search}%"))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'vitamin_b12_level' => 'number',
                'report_date' => 'date',
                'created_at' => 'date',
            ]))
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     vitamin_b12_level: float|int|null
         * }> $chartRows
         */
        $chartRows = VitaminB12::query()
            ->selectRaw('DATE(report_date) as label, AVG(vitamin_b12_level) as vitamin_b12_level')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'vitamin_b12_level' => [
                    'label' => __('main.menu.sidebar_items.exams_items.vitamin_b12'),
                    'data' => $row->vitamin_b12_level,
                ],
            ],
        ])->toArray();

        return [$vitaminB12s, $chartData];
    }
}
