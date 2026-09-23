<?php

namespace App\Services\Exams;

use App\Common\DataTableQuery;
use App\Interfaces\Exams\CompleteBloodCountServiceInterface;
use App\Models\Exams\CompleteBloodCount;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CompleteBloodCountService implements CompleteBloodCountServiceInterface
{
    /**
     * Fetch paginated data and chart data for this exam type based on the provided parameters.
     *
     * @param  list<array{id: string, desc: bool}>  $sorting
     * @param  list<array{id: string, operator: string, value?: mixed, joinOperator?: string, filterId?: string}>|null  $filters
     * @return array{0: LengthAwarePaginator<int, CompleteBloodCount>, 1: array<string, mixed>}
     */
    public function getCompleteBloodCountData(?int $perPage, array $sorting, ?string $search, ?array $filters): array
    {

        $completeBloodCounts = CompleteBloodCount::query()
            ->select('complete_blood_counts.*')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->whereLike('hematocrit', "%{$search}%")
                    ->orWhereLike('hemoglobin', "%{$search}%")
                    ->orWhereLike('red_blood_cell_count', "%{$search}%")
                    ->orWhereLike('mean_corpuscular_volume', "%{$search}%")
                    ->orWhereLike('mean_corpuscular_hemoglobin', "%{$search}%")
                    ->orWhereLike('mean_corpuscular_hemoglobin_concentration', "%{$search}%")
                    ->orWhereLike('red_blood_cell_distribution_width', "%{$search}%")
                    ->orWhereLike('leukocyte_count', "%{$search}%")
                    ->orWhereLike('rod_neutrophil_count', "%{$search}%")
                    ->orWhereLike('segmented_neutrophil_count', "%{$search}%")
                    ->orWhereLike('lymphocyte_count', "%{$search}%")
                    ->orWhereLike('monocyte_count', "%{$search}%")
                    ->orWhereLike('eosinophil_count', "%{$search}%")
                    ->orWhereLike('basophil_count', "%{$search}%")
                    ->orWhereLike('metamyelocyte_count', "%{$search}%")
                    ->orWhereLike('promyelocyte_count', "%{$search}%")
                    ->orWhereLike('atypical_cell_count', "%{$search}%")
                    ->orWhereLike('platelet_count', "%{$search}%");
            }))
            ->tap(fn ($query) => DataTableQuery::apply($query, $filters ?? [], $sorting, [
                'id' => 'number',
                'hematocrit' => 'number',
                'hemoglobin' => 'number',
                'red_blood_cell_count' => 'number',
                'mean_corpuscular_volume' => 'number',
                'mean_corpuscular_hemoglobin' => 'number',
                'mean_corpuscular_hemoglobin_concentration' => 'number',
                'red_blood_cell_distribution_width' => 'number',
                'leukocyte_count' => 'number',
                'rod_neutrophil_count' => 'number',
                'segmented_neutrophil_count' => 'number',
                'lymphocyte_count' => 'number',
                'monocyte_count' => 'number',
                'eosinophil_count' => 'number',
                'basophil_count' => 'number',
                'metamyelocyte_count' => 'number',
                'promyelocyte_count' => 'number',
                'atypical_cell_count' => 'number',
                'platelet_count' => 'number',
                'report_date' => 'date',
                'created_at' => 'date',
            ]))
            ->paginate($perPage)
            ->withQueryString();

        /**
         * @var Collection<int, object{
         *     label: string,
         *     hematocrit: float|int|null,
         *     hemoglobin: float|int|null,
         *     red_blood_cell_count: float|int|null,
         *     leukocyte_count: float|int|null,
         *     platelet_count: float|int|null
         * }> $chartRows
         */
        $chartRows = CompleteBloodCount::query()
            ->selectRaw('DATE(report_date) as label, AVG(hematocrit) as hematocrit, AVG(hemoglobin) as hemoglobin, AVG(red_blood_cell_count) as red_blood_cell_count, AVG(leukocyte_count) as leukocyte_count, AVG(platelet_count) as platelet_count')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn (object $row): array => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'hematocrit' => ['label' => 'Hematocrit', 'data' => $row->hematocrit],
                'hemoglobin' => ['label' => 'Hemoglobin', 'data' => $row->hemoglobin],
                'red_blood_cell_count' => ['label' => 'Red Blood Cell Count', 'data' => $row->red_blood_cell_count],
                'leukocyte_count' => ['label' => 'White Blood Cell Count', 'data' => $row->leukocyte_count],
                'platelet_count' => ['label' => 'Platelet Count', 'data' => $row->platelet_count],
            ],
        ])->toArray();

        return [$completeBloodCounts, $chartData];
    }
}
