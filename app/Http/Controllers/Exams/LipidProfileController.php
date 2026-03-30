<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Services\Exams\LipidProfileService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

use function in_array;

class LipidProfileController extends Controller
{
    public function __construct(private LipidProfileService $lipidProfileService) {}

    public function index(QueryRequest $request)
    {
        [$lipidProfile, $chartData] = $this->lipidProfilePageAndChartData($request);

        return Inertia::render('exams/lipid-profile/index', [
            'lipidProfile' => $lipidProfile,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Validate request query inputs, apply sort/filter defaults, and fetch
     * paginated lipid profile results plus chart data.
     *
     * @return array{
     *     0: LengthAwarePaginator,
     *     1: array<string, mixed>
     * }
     */
    private function lipidProfilePageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'report_date', 'total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides', 'created_at'];

        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->lipidProfileService->getLipidProfileData($perPage, $sortBy, $sortDir, $search, $filters);
    }
}
