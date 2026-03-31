<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Services\Exams\CompleteBloodCountService;
use Inertia\Inertia;

use function in_array;

class CompleteBloodCountController extends Controller
{
    public function __construct(private CompleteBloodCountService $completeBloodCountService) {}

    public function index(QueryRequest $request)
    {
        [$completeBloodCount, $chartData] = $this->completeBloodCountPageAndChartData($request);

        return Inertia::render('exams/complete-blood-count/index', [
            'completeBloodCount' => $completeBloodCount,
            'chartData' => $chartData,
        ]);
    }

    public function create()
    {
        return Inertia::render('exams/complete-blood-count/create');
    }

    private function completeBloodCountPageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'hematocrit', 'hemoglobin', 'red_blood_cell_count', 'mean_corpuscular_volume', 'mean_corpuscular_hemoglobin',
            'mean_corpuscular_hemoglobin_concentration', 'red_blood_cell_distribution_width', 'leukocyte_count', 'rod_neutrophil_count',
            'segmented_neutrophil_count', 'lymphocyte_count', 'monocyte_count', 'eosinophil_count', 'basophil_count', 'metamyelocyte_count',
            'promyelocyte_count', 'atypical_cell_count', 'platelet_count', 'report_date', 'created_at'];

        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->completeBloodCountService->getCompleteBloodCountData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
