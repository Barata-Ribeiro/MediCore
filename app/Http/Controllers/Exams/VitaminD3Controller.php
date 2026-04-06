<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Services\Exams\VitaminD3Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

use function in_array;

class VitaminD3Controller extends Controller
{
    public function __construct(private VitaminD3Service $vitaminD3Service) {}

    public function index(QueryRequest $request)
    {
        [$vitaminD3s, $chartData] = $this->vitaminD3PageAndChartData($request);

        return Inertia::render('exams/vitamin-d3/index', [
            'vitaminD3s' => $vitaminD3s,
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
    private function vitaminD3PageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'twenty_five_hydroxyvitamin_d3', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->vitaminD3Service->getVitaminD3sData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
