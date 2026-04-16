<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\UltrasensitiveTshServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

use function in_array;

class UltrasensitiveTshController extends Controller
{
    public function __construct(private UltrasensitiveTshServiceInterface $ultrasensitiveTshService) {}

    public function index(QueryRequest $request)
    {
        [$ultrasensitiveTshs, $chartData] = $this->ultrasensitiveTshPageAndChartData($request);

        return Inertia::render('exams/ultrasensitive-tsh/index', [
            'ultrasensitiveTshs' => $ultrasensitiveTshs,
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
    private function ultrasensitiveTshPageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'tsh_value', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->ultrasensitiveTshService->getUltrasensitiveTshsData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
