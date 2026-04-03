<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\CompleteBloodCountServiceInterface;
use App\Models\Exams\CompleteBloodCount;
use Concurrency;

class CompleteBloodCountService implements CompleteBloodCountServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCompleteBloodCountData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $completeBloodCounts = CompleteBloodCount::query()
            ->select('complete_blood_counts.*')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('complete_blood_counts.created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('complete_blood_counts.report_date', [$reportDateStart, $reportDateEnd]))
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
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = CompleteBloodCount::query()
            ->selectRaw('DATE(report_date) as label, AVG(hematocrit) as hematocrit, AVG(hemoglobin) as hemoglobin, AVG(red_blood_cell_count) as red_blood_cell_count, AVG(leukocyte_count) as leukocyte_count, AVG(platelet_count) as platelet_count')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'hematocrit' => ['label' => 'Hematocrit', 'data' => $row->hematocrit],
                'hemoglobin' => ['label' => 'Hemoglobin', 'data' => $row->hemoglobin],
                'red_blood_cell_count' => ['label' => 'Red Blood Cell Count', 'data' => $row->red_blood_cell_count],
                'leukocyte_count' => ['label' => 'White Blood Cell Count', 'data' => $row->leukocyte_count],
                'platelet_count' => ['label' => 'Platelet Count', 'data' => $row->platelet_count],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$completeBloodCounts, $chartData];
        }

        return Concurrency::run([
            fn () => $completeBloodCounts,
            fn () => $chartData,
        ]);
    }
}
