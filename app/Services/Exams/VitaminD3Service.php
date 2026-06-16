<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\VitaminD3ServiceInterface;
use App\Models\Exams\VitaminD3;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VitaminD3Service implements VitaminD3ServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  array<string, mixed>|null  $filters
     * @return array{0: LengthAwarePaginator<int, VitaminD3>, 1: array<string, mixed>}
     */
    public function getVitaminD3sData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $vitaminD3s = VitaminD3::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('twenty_five_hydroxyvitamin_d3', "%{$search}%"))
            ->orderBy($sortBy ?? 'created_at', $sortDir === 'desc' ? 'desc' : 'asc')
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
