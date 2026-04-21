<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\CompleteBloodCountRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\CompleteBloodCountServiceInterface;
use App\Models\Exams\CompleteBloodCount;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

use function in_array;

class CompleteBloodCountController extends Controller
{
    public function __construct(private CompleteBloodCountServiceInterface $completeBloodCountService) {}

    public function index(QueryRequest $request)
    {
        [$completeBloodCounts, $chartData] = $this->completeBloodCountPageAndChartData($request);

        return Inertia::render('exams/complete-blood-count/index', [
            'completeBloodCounts' => $completeBloodCounts,
            'chartData' => $chartData,
        ]);
    }

    public function create()
    {
        return Inertia::render('exams/complete-blood-count/create');
    }

    public function store(CompleteBloodCountRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->medicalFile->completeBloodCounts()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.cbc.store_successfully')]);

            return to_route('complete-blood-count.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.cbc.store_failed')]);
            Log::error('Error creating complete blood count', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(CompleteBloodCount $completeBloodCount)
    {
        return Inertia::render('exams/complete-blood-count/edit', [
            'completeBloodCount' => $completeBloodCount,
        ]);
    }

    public function update(CompleteBloodCountRequest $request, CompleteBloodCount $completeBloodCount)
    {
        $user = $request->user();

        if ($completeBloodCount->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.cbc.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            $completeBloodCount->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.cbc.update_successfully')]);

            return to_route('complete-blood-count.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.cbc.update_failed')]);
            Log::error('Error updating complete blood count', ['user_id' => $user->id, 'record_id' => $completeBloodCount->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function destroy(CompleteBloodCount $completeBloodCount)
    {
        $user = request()->user();

        if ($completeBloodCount->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.cbc.destroy_unauthorized')]);

            return back();
        }

        try {
            $completeBloodCount->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.cbc.destroy_successfully')]);

            return to_route('complete-blood-count.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.cbc.destroy_failed')]);
            Log::error('Error deleting complete blood count', ['user_id' => $user->id, 'record_id' => $completeBloodCount->id, 'error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Validate request query inputs, apply sort/filter defaults, and fetch
     * paginated complete blood count results plus chart data.
     *
     * @return array{
     *     0: LengthAwarePaginator,
     *     1: array<string, mixed>
     * }
     */
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
