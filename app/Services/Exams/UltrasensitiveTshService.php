<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\UltrasensitiveTshServiceInterface;
use App\Models\Exams\UltrasensitiveTsh;
use Concurrency;

class UltrasensitiveTshService implements UltrasensitiveTshServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUltrasensitiveTshsData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $ultrasensitiveTshs = UltrasensitiveTsh::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('tsh_value', "%{$search}%"))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = UltrasensitiveTsh::query()
            ->selectRaw('DATE(report_date) as label, AVG(tsh_value) as tsh_value')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'tsh_value' => ['label' => 'TSH Value', 'data' => $row->tsh_value],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$ultrasensitiveTshs, $chartData];
        }

        return Concurrency::run([
            fn () => $ultrasensitiveTshs,
            fn () => $chartData,
        ]);
    }
}
