<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\TotalProteinsAndFractionsServiceInterface;
use App\Models\Exams\TotalProteinsAndFractions;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

use function in_array;

class TotalProteinsAndFractionsController extends Controller
{
    public function __construct(private TotalProteinsAndFractionsServiceInterface $totalProteinsAndFractionsService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(QueryRequest $request): Response
    {
        syncLangFiles('total_proteins_and_fractions_pages');

        [$totalProteinsAndFractions, $chartData] = $this->totalProteinsAndFractionsPageAndChartData($request);

        return Inertia::render('exams/total-proteins-and-fractions/index', [
            'totalProteinsAndFractions' => $totalProteinsAndFractions,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TotalProteinsAndFractions $totalProteinsAndFractions)
    {
        //
    }

    /**
     * Validate request query inputs, apply sort/filter defaults, and fetch
     * paginated total proteins and fractions results plus chart data.
     *
     * @return array{
     *     0: LengthAwarePaginator<int, TotalProteinsAndFractions>,
     *     1: array<string, mixed>
     * }
     */
    private function totalProteinsAndFractionsPageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'total_proteins', 'albumin', 'globulin', 'albumin_globulin_ratio', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->totalProteinsAndFractionsService->getTotalProteinsAndFractionsData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
