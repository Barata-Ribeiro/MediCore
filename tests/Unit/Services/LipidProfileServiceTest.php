<?php

use App\Models\User;
use App\Services\Exams\LipidProfileService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

it('returns lipid profile chart data grouped by date', function () {
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();

    $reportDate = now()->subDays(1)->toDateString();

    $medicalFile->lipidProfile()->create([
        'total_cholesterol' => 100,
        'hdl_cholesterol' => 50,
        'ldl_cholesterol' => 20,
        'vldl_cholesterol' => 10,
        'triglycerides' => 80,
        'report_date' => $reportDate,
    ]);

    $medicalFile->lipidProfile()->create([
        'total_cholesterol' => 120,
        'hdl_cholesterol' => 60,
        'ldl_cholesterol' => 30,
        'vldl_cholesterol' => 15,
        'triglycerides' => 90,
        'report_date' => $reportDate,
    ]);

    $this->actingAs($user);

    /** @var LipidProfileService $service */
    $service = app(LipidProfileService::class);

    [$paginator, $chartData] = $service->getLipidProfileData(perPage: 10, sortBy: 'id', sortDir: 'asc', search: '', filters: []);

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($chartData)->toHaveCount(1);
    expect($chartData[0]->date)->toBe($reportDate);
    expect((int) $chartData[0]->count)->toBe(2);
});
