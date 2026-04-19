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
            ->with(['profile', 'medicalFile' => fn ($q) => $q->selectRaw('medical_files.*,
                (SELECT COUNT(*) FROM complete_blood_counts WHERE medical_file_id = medical_files.id) AS complete_blood_counts_count,
                (SELECT COUNT(*) FROM lipid_profiles WHERE medical_file_id = medical_files.id) AS lipid_profiles_count,
                (SELECT COUNT(*) FROM glucoses WHERE medical_file_id = medical_files.id) AS glucoses_count,
                (SELECT COUNT(*) FROM ultrasensitive_tshs WHERE medical_file_id = medical_files.id) AS ultrasensitive_tshs_count,
                (SELECT COUNT(*) FROM urea_and_creatinines WHERE medical_file_id = medical_files.id) AS urea_and_creatinines_count,
                (SELECT COUNT(*) FROM vitamin_d3_s WHERE medical_file_id = medical_files.id) AS vitamin_d3s_count,
                (SELECT COUNT(*) FROM vitamin_b12_s WHERE medical_file_id = medical_files.id) AS vitamin_b12s_count')])
            ->where('id', auth()->id())
            ->first();

        $medicalFile = $data->medicalFile;
        $completeBloodCountCount = $medicalFile?->complete_blood_counts_count ?? 0;
        $glucoseCount = $medicalFile?->glucoses_count ?? 0;
        $lipidProfileCount = $medicalFile?->lipid_profiles_count ?? 0;
        $ultrasensitiveTshCount = $medicalFile?->ultrasensitive_tshs_count ?? 0;
        $ureaAndCreatinineCount = $medicalFile?->urea_and_creatinines_count ?? 0;
        $vitaminD3Count = $medicalFile?->vitamin_d3s_count ?? 0;
        $vitaminB12Count = $medicalFile?->vitamin_b12s_count ?? 0;

        $totalCount = $lipidProfileCount + $completeBloodCountCount + $glucoseCount + $vitaminD3Count + $vitaminB12Count + $ultrasensitiveTshCount + $ureaAndCreatinineCount;

        $medicalFile?->makeHidden([
            'complete_blood_counts_count',
            'glucoses_count',
            'lipid_profiles_count',
            'ultrasensitive_tshs_count',
            'urea_and_creatinines_count',
            'vitamin_d3s_count',
            'vitamin_b12s_count',
        ]);

        return [
            'profile' => $data->profile,
            'medicalFile' => $medicalFile,
            'exams' => [
                'cbc_count' => $completeBloodCountCount,
                'glucoses_count' => $glucoseCount,
                'lipid_profiles_count' => $lipidProfileCount,
                'ultrasensitive_tshs_count' => $ultrasensitiveTshCount,
                'urea_and_creatinines_count' => $ureaAndCreatinineCount,
                'vitamin_d3s_count' => $vitaminD3Count,
                'vitamin_b12s_count' => $vitaminB12Count,
                'total' => $totalCount,
            ],
        ];
    }
}
