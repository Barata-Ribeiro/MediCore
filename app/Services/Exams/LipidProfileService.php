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

        $lipidProfile = LipidProfile::query()
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

        $chartData = LipidProfile::query()
            ->selectRaw('DATE(report_date) as date, AVG(total_cholesterol) as total_cholesterol, AVG(hdl_cholesterol) as hdl_cholesterol, AVG(ldl_cholesterol) as ldl_cholesterol, AVG(vldl_cholesterol) as vldl_cholesterol, AVG(triglycerides) as triglycerides, AVG(total_cholesterol / NULLIF(hdl_cholesterol, 0)) as ratio, count(*) as count')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupByRaw('DATE(report_date)')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        if (app()->environment('testing')) {
            return [$lipidProfile, $chartData];
        }

        return Concurrency::run([
            fn () => $lipidProfile,
            fn () => $chartData,
        ]);
    }
}
