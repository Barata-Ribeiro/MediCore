<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\VitaminD3ServiceInterface;
use App\Models\Exams\VitaminD3;
use Concurrency;

class VitaminD3Service implements VitaminD3ServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getVitaminD3sData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $vitaminD3s = VitaminD3::query()
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->whereLike('twenty_five_hydroxyvitamin_d3', "%{$search}%"))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = VitaminD3::query()
            ->selectRaw('DATE(report_date) as label, AVG(twenty_five_hydroxyvitamin_d3) as twenty_five_hydroxyvitamin_d3')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'twenty_five_hydroxyvitamin_d3' => ['label' => '25-Hydroxyvitamin D3', 'data' => $row->twenty_five_hydroxyvitamin_d3],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$vitaminD3s, $chartData];
        }

        return Concurrency::run([
            fn () => $vitaminD3s,
            fn () => $chartData,
        ]);

    }
}
