<?php

namespace App\Services\Exams;

use App\Common\Helpers;
use App\Interfaces\Exams\LipidProfileServiceInterface;
use App\Models\Exams\LipidProfile;
use Concurrency;

class LipidProfileService implements LipidProfileServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getLipidProfileData(?int $perPage, ?string $sortBy, ?string $sortDir, ?string $search, ?array $filters): array
    {
        $createdAtRange = $filters['created_at'] ?? [];
        $reportDateRange = $filters['report_date'] ?? [];

        [$createdAtStart, $createdAtEnd] = Helpers::getDateRange($createdAtRange);
        [$reportDateStart, $reportDateEnd] = Helpers::getDateRange($reportDateRange);

        $lipidProfiles = LipidProfile::query()
            ->select('lipid_profiles.*')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->when($createdAtRange, fn ($q) => $q->whereBetween('lipid_profiles.created_at', [$createdAtStart, $createdAtEnd]))
            ->when($reportDateRange, fn ($q) => $q->whereBetween('lipid_profiles.report_date', [$reportDateStart, $reportDateEnd]))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->whereLike('total_cholesterol', "%{$search}%")
                    ->orWhereLike('hdl_cholesterol', "%{$search}%")
                    ->orWhereLike('ldl_cholesterol', "%{$search}%")
                    ->orWhereLike('vldl_cholesterol', "%{$search}%")
                    ->orWhereLike('triglycerides', "%{$search}%");
            }))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $chartRows = LipidProfile::query()
            ->selectRaw('DATE(report_date) as label, AVG(total_cholesterol) as total_cholesterol, AVG(hdl_cholesterol) as hdl_cholesterol, AVG(ldl_cholesterol) as ldl_cholesterol, AVG(vldl_cholesterol) as vldl_cholesterol, AVG(triglycerides) as triglycerides')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        $chartData = $chartRows->map(fn ($row) => [
            'x_axis_label' => $row->label,
            'datasets' => [
                'total_cholesterol' => ['label' => 'Total Cholesterol', 'data' => $row->total_cholesterol],
                'hdl_cholesterol' => ['label' => 'HDL Cholesterol', 'data' => $row->hdl_cholesterol],
                'ldl_cholesterol' => ['label' => 'LDL Cholesterol', 'data' => $row->ldl_cholesterol],
                'vldl_cholesterol' => ['label' => 'VLDL Cholesterol', 'data' => $row->vldl_cholesterol],
                'triglycerides' => ['label' => 'Triglycerides', 'data' => $row->triglycerides],
            ],
        ])->toArray();

        if (app()->environment('testing')) {
            return [$lipidProfiles, $chartData];
        }

        return Concurrency::run([
            fn () => $lipidProfiles,
            fn () => $chartData,
        ]);
    }
}
