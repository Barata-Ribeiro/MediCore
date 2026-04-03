<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\GlucoseRequest;
use App\Http\Requests\QueryRequest;
use App\Services\Exams\GlucoseService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

use function in_array;

class GlucoseController extends Controller
{
    public function __construct(private GlucoseService $glucoseService) {}

    public function index(QueryRequest $request)
    {
        [$glucoses, $chartData] = $this->glucosePageAndChartData($request);

        return Inertia::render('exams/glucose/index', [
            'glucoses' => $glucoses,
            'chartData' => $chartData,
        ]);
    }

    public function create()
    {
        return Inertia::render('exams/glucose/create');
    }

    public function store(GlucoseRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->medicalFile->glucoses()->create($validated);

            Inertia::flash('success', 'Glucose record created successfully.');

            return to_route('glucose.index');
        } catch (Exception $e) {
            Inertia::flash('error', 'An error occurred while creating the glucose record.');
            Log::error('Error creating glucose record', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
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
    private function glucosePageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'glucose_level', 'glycated_hemoglobin', 'estimated_average_glucose', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->glucoseService->getGlucosePageAndChartData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
