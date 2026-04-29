<?php

namespace App\Interfaces;

interface DashboardServiceInterface
{
    /**
     * Get the dashboard data for the authenticated user, including profile information, medical file details, and counts of various exams.
     *
     * @return array{
     *     profile: array<string, mixed>,
     *     medical_file: array<string, mixed>,
     *     exam_counts: array<string, int>
     * }
     */
    public function getDashboardData(): array;
}
