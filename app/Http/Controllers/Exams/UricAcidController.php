<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\UricAcidServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

use function in_array;

class UricAcidController extends Controller
{
    public function __construct(private UricAcidServiceInterface $uricAcidService) {}

    public function index(QueryRequest $request)
    {
        [$uricAcids, $chartData] = $this->uricAcidPageAndChartData($request);

        return Inertia::render('exams/uric-acid/index', [
            'uricAcids' => $uricAcids,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Validate request query inputs, apply sort/filter defaults, and fetch
     * paginated glucose results plus chart data.
     *
     * @return array{
     *     0: LengthAwarePaginator,
     *     1: array<string, mixed>
     * }
     */
    private function uricAcidPageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'uric_acid_level', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->uricAcidService->getUricAcidsData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
