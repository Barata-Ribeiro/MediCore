<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\TotalProteinsAndFractionsServiceInterface;
use App\Models\Exams\TotalProteinsAndFractions;
use Illuminate\Support\Collection;

class TotalProteinsAndFractionsService implements TotalProteinsAndFractionsServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getTotalProteinsAndFractionsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $filters ??= [];
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $tpfs = TotalProteinsAndFractions::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('total_proteins', "%{$search}%")
                ->orWhereLike('albumin', "%{$search}%")
                ->orWhereLike('globulin', "%{$search}%"))
            ->orderBy($sortBy ?? 'created_at', $sortDir === 'desc' ? 'desc' : 'asc')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     total_proteins: float|int|null,
         *     albumin: float|int|null,
         *     globulin: float|int|null,
         *     albumin_globulin_ratio: float|int|null
         * }> $chartRows
         */
        $chartRows = TotalProteinsAndFractions::query()
            ->selectRaw('DATE(report_date) as label, AVG(total_proteins) as total_proteins, AVG(albumin) as albumin, AVG(globulin) as globulin, AVG(albumin_globulin_ratio) as albumin_globulin_ratio')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'total_proteins' => ['label' => __('total_proteins_and_fractions_pages.index.table.columns.total_proteins'), 'data' => $row->total_proteins],
                'albumin' => ['label' => __('total_proteins_and_fractions_pages.index.table.columns.albumin'), 'data' => $row->albumin],
                'globulin' => ['label' => __('total_proteins_and_fractions_pages.index.table.columns.globulin'), 'data' => $row->globulin],
                'albumin_globulin_ratio' => ['label' => __('total_proteins_and_fractions_pages.index.table.columns.albumin_globulin_ratio'), 'data' => $row->albumin_globulin_ratio],
            ],
        ])->toArray();

        return [$tpfs, $chartData];
    }
}
