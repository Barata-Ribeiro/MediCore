<?php

namespace App\Services;

use App\Interfaces\DashboardServiceInterface;
use App\Models\User;

class DashboardService implements DashboardServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getDashboardData(): array
    {
        $data = User::query()
            ->with(['profile', 'medicalFile' => fn ($q) => $q->withCount(['lipidProfiles', 'completeBloodCounts', 'glucoses'])])
            ->where('id', auth()->id())
            ->first();

        $medicalFile = $data->medicalFile;
        $lipidProfileCount = $medicalFile?->lipid_profiles_count ?? 0;
        $completeBloodCountCount = $medicalFile?->complete_blood_counts_count ?? 0;
        $glucoseCount = $medicalFile?->glucoses_count ?? 0;
        $totalCount = $lipidProfileCount + $completeBloodCountCount + $glucoseCount;

        $medicalFile?->makeHidden([
            'lipid_profiles_count',
            'complete_blood_counts_count',
            'glucoses_count',
        ]);

        return [
            'profile' => $data->profile,
            'medicalFile' => $medicalFile,
            'exams' => [
                'total' => $totalCount,
                'lipid_profiles_count' => $lipidProfileCount,
                'cbc_count' => $completeBloodCountCount,
                'glucoses_count' => $glucoseCount,
            ],
        ];
    }
}
