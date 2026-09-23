<?php

namespace App\Services\Exams;

use App\Common\DataTableQuery;
use App\Interfaces\Exams\VitaminD3ServiceInterface;
use App\Models\Exams\VitaminD3;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VitaminD3Service implements VitaminD3ServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{0: LengthAwarePaginator<int, VitaminD3>, 1: array<string, mixed>}
     */
    public function getVitaminD3sData(?int $perPage, array $sorting, ?string $search, ?array $filters): array
    {

        $vitaminD3s = VitaminD3::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($search, fn ($q) => $q->whereLike('twenty_five_hydroxyvitamin_d3', "%{$search}%"))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'twenty_five_hydroxyvitamin_d3' => 'number',
                'report_date' => 'date',
                'created_at' => 'date',
            ]))
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     twenty_five_hydroxyvitamin_d3: float|int|null
         * }> $chartRows
         */
        $chartRows = VitaminD3::query()
            ->selectRaw('DATE(report_date) as label, AVG(twenty_five_hydroxyvitamin_d3) as twenty_five_hydroxyvitamin_d3')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'twenty_five_hydroxyvitamin_d3' => ['label' => '25-Hydroxyvitamin D3', 'data' => $row->twenty_five_hydroxyvitamin_d3],
            ],
        ])->toArray();

        return [$vitaminD3s, $chartData];

    }
}
