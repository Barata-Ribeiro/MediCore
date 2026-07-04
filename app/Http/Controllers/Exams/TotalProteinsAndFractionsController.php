<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\TotalProteinsAndFractionsRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\TotalProteinsAndFractionsServiceInterface;
use App\Models\Exams\TotalProteinsAndFractions;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Log;

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
    public function create(): Response
    {
        syncLangFiles('total_proteins_and_fractions_pages');

        return Inertia::render('exams/total-proteins-and-fractions/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TotalProteinsAndFractionsRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->medicalFile->totalProteinsAndFractions()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.total_proteins_and_fractions.store_successfully')]);

            return to_route('total_proteins_and_fractions.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.total_proteins_and_fractions.store_failed')]);
            Log::error('Error creating Total Proteins and Fractions record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TotalProteinsAndFractions $totalProteinsAndFractions): Response
    {
        syncLangFiles('total_proteins_and_fractions_pages');

        return Inertia::render('exams/total-proteins-and-fractions/edit', [
            'totalProteinsAndFractions' => $totalProteinsAndFractions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TotalProteinsAndFractionsRequest $request, TotalProteinsAndFractions $totalProteinsAndFractions): RedirectResponse
    {
        $user = $request->user();

        if ($totalProteinsAndFractions->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.total_proteins_and_fractions.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            $totalProteinsAndFractions->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.total_proteins_and_fractions.update_successfully')]);

            return to_route('total_proteins_and_fractions.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.total_proteins_and_fractions.update_failed')]);
            Log::error('Error updating Total Proteins and Fractions record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TotalProteinsAndFractions $totalProteinsAndFractions): RedirectResponse
    {
        $user = auth()->user();

        if ($totalProteinsAndFractions->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.total_proteins_and_fractions.destroy_unauthorized')]);

            return back();
        }

        try {
            $totalProteinsAndFractions->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.total_proteins_and_fractions.destroy_successfully')]);

            return to_route('total_proteins_and_fractions.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.total_proteins_and_fractions.destroy_failed')]);
            Log::error('Error deleting Total Proteins and Fractions record', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back();
        }
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
