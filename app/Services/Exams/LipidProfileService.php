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
            ->selectRaw('DATE(report_date) as date, total_cholesterol, hdl_cholesterol, ldl_cholesterol, vldl_cholesterol, triglycerides, (total_cholesterol / hdl_cholesterol) as ratio, count(*) as count')
            ->where('medical_file_id', auth()->user()->medicalFile->id)
            ->groupBy('date')
            ->orderBy('date')
            ->limit(5)
            ->get();

        return Concurrency::run([
            fn () => $lipidProfile,
            fn () => $chartData,
        ]);
    }
}
