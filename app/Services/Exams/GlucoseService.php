<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\GlucoseServiceInterface;
use App\Models\Exams\Glucose;
use Concurrency;

class GlucoseService implements GlucoseServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getGlucosePageAndChartData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $glucoses = Glucose::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->whereLike('glucose_level', "%{$search}%")
                    ->orWhereLike('glycated_hemoglobin', "%{$search}%")
                    ->orWhereLike('estimated_average_glucose', "%{$search}%");
            }))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = Glucose::query()
            ->selectRaw('DATE(report_date) as label, AVG(glucose_level) as glucose_level, AVG(glycated_hemoglobin) as glycated_hemoglobin, AVG(estimated_average_glucose) as estimated_average_glucose')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'glucose_level' => ['label' => 'Glucose Level', 'data' => $row->glucose_level],
                'glycated_hemoglobin' => ['label' => 'Glycated Hemoglobin', 'data' => $row->glycated_hemoglobin],
                'estimated_average_glucose' => ['label' => 'Estimated Average Glucose', 'data' => $row->estimated_average_glucose],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$glucoses, $chartData];
        }

        return Concurrency::run([
            fn () => $glucoses,
            fn () => $chartData,
        ]);
    }
}
