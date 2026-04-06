<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\VitaminD3Request;
use App\Http\Requests\QueryRequest;
use App\Services\Exams\VitaminD3Service;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

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

    public function create()
    {
        return Inertia::render('exams/vitamin-d3/create');
    }

    public function store(VitaminD3Request $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {

            $user->medicalFile->vitaminD3s()->create($validated);

            Inertia::flash('success', 'Vitamin D3 record created successfully.');

            return to_route('vitamin-d3.index');
        } catch (Exception $e) {
            Inertia::flash('error', 'An error occurred while creating the Vitamin D3 record.');
            Log::error('Error creating Vitamin D3 record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

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
