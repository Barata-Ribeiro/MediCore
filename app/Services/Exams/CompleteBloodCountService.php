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

        $completeBloodCount = CompleteBloodCount::query()
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

        $chartData = CompleteBloodCount::query()
            ->selectRaw('DATE(report_date) as date, AVG(hematocrit) as hematocrit, AVG(hemoglobin) as hemoglobin, AVG(red_blood_cell_count) as red_blood_cell_count, AVG(mean_corpuscular_volume) as mean_corpuscular_volume, AVG(mean_corpuscular_hemoglobin) as mean_corpuscular_hemoglobin, AVG(mean_corpuscular_hemoglobin_concentration) as mean_corpuscular_hemoglobin_concentration, AVG(red_blood_cell_distribution_width) as red_blood_cell_distribution_width, AVG(leukocyte_count) as leukocyte_count, AVG(rod_neutrophil_count) as rod_neutrophil_count, AVG(segmented_neutrophil_count) as segmented_neutrophil_count, AVG(lymphocyte_count) as lymphocyte_count, AVG(monocyte_count) as monocyte_count, AVG(eosinophil_count) as eosinophil_count, AVG(basophil_count) as basophil_count, AVG(metamyelocyte_count) as metamyelocyte_count, AVG(promyelocyte_count) as promyelocyte_count, AVG(atypical_cell_count) as atypical_cell_count, AVG(platelet_count) as platelet_count, count(*) as count')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupByRaw('DATE(report_date)')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        if (app()->environment('testing')) {
            return [$completeBloodCount, $chartData];
        }

        return Concurrency::run([
            fn () => $completeBloodCount,
            fn () => $chartData,
        ]);
    }
}
