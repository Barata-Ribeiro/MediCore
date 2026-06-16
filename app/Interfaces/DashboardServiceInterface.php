<?php

namespace App\Interfaces;

use App\Models\MedicalFile;
use App\Models\Profile;

interface DashboardServiceInterface
{
    /**
     * Get the dashboard data for the authenticated user, including profile information, medical file details, and counts of various exams.
     *
     * @return array{
     *     profile: Profile|null,
     *     medicalFile: MedicalFile|null,
     *     exams: array{
     *         cbc_count: int,
     *         glucoses_count: int,
     *         lipid_profiles_count: int,
     *         ultrasensitive_tshs_count: int,
     *         urea_and_creatinines_count: int,
     *         vitamin_d3s_count: int,
     *         vitamin_b12s_count: int,
     *         total: int,
     *     }
     * }
     */
    public function getDashboardData(): array;
}
