<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\VitaminD3Request;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\VitaminD3ServiceInterface;
use App\Models\Exams\VitaminD3;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

use function in_array;

class VitaminD3Controller extends Controller
{
    public function __construct(private VitaminD3ServiceInterface $vitaminD3Service) {}

    public function index(QueryRequest $request)
    {
        syncLangFiles('exams/vitamin_d3_pages');

        [$vitaminD3s, $chartData] = $this->vitaminD3PageAndChartData($request);

        return Inertia::render('exams/vitamin-d3/index', [
            'vitaminD3s' => $vitaminD3s,
            'chartData' => $chartData,
        ]);
    }

    public function create()
    {
        syncLangFiles('exams/vitamin_d3_pages');

        return Inertia::render('exams/vitamin-d3/create');
    }

    public function store(VitaminD3Request $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {

            $user->medicalFile->vitaminD3s()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.vitamin_d3.store_successfully')]);

            return to_route('vitamin-d3.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.vitamin_d3.store_failed')]);
            Log::error('Error creating Vitamin D3 record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(VitaminD3 $vitaminD3)
    {
        syncLangFiles('exams/vitamin_d3_pages');

        return Inertia::render('exams/vitamin-d3/edit', [
            'vitaminD3' => $vitaminD3,
        ]);
    }

    public function update(VitaminD3Request $request, VitaminD3 $vitaminD3)
    {
        $user = $request->user();

        if ($vitaminD3->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.vitamin_d3.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            $vitaminD3->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.vitamin_d3.update_successfully')]);

            return to_route('vitamin-d3.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.vitamin_d3.update_failed')]);
            Log::error('Error updating Vitamin D3 record', ['user_id' => $request->user()->id, 'vitamin_d3_id' => $vitaminD3->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }

    }

    public function destroy(VitaminD3 $vitaminD3)
    {
        $user = auth()->user();

        if ($vitaminD3->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.vitamin_d3.destroy_unauthorized')]);

            return back();
        }

        try {
            $vitaminD3->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.vitamin_d3.destroy_successfully')]);

            return to_route('vitamin-d3.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.vitamin_d3.destroy_failed')]);
            Log::error('Error deleting Vitamin D3 record', ['user_id' => $user->id, 'vitamin_d3_id' => $vitaminD3->id, 'error' => $e->getMessage()]);

            return back();
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
