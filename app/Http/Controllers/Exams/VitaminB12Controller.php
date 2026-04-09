<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\VitaminB12ServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

use function in_array;

class VitaminB12Controller extends Controller
{
    public function __construct(private VitaminB12ServiceInterface $vitaminB12Service) {}

    public function index(QueryRequest $request)
    {
        [$vitaminB12s, $chartData] = $this->vitaminB12PageAndChartData($request);

        return Inertia::render('exams/vitamin-b12/index', [
            'vitaminB12s' => $vitaminB12s,
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
    private function vitaminB12PageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'vitamin_b12_level', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->vitaminB12Service->getVitaminB12sData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
