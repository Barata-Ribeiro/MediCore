<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\VitaminD3Request;
use App\Http\Requests\QueryRequest;
use App\Models\Exams\VitaminD3;
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

    public function edit(VitaminD3 $vitaminD3)
    {
        return Inertia::render('exams/vitamin-d3/edit', [
            'vitaminD3' => $vitaminD3,
        ]);
    }

    public function update(VitaminD3Request $request, VitaminD3 $vitaminD3)
    {
        $user = $request->user();

        if ($vitaminD3->medicalFile->user_id !== $user->id) {
            Inertia::flash('error', 'Unauthorized to update this Vitamin D3 record.');

            return back();
        }

        $validated = $request->validated();

        try {
            $vitaminD3->update($validated);

            Inertia::flash('success', 'Vitamin D3 record updated successfully.');

            return to_route('vitamin-d3.index');
        } catch (Exception $e) {
            Inertia::flash('error', 'An error occurred while updating the Vitamin D3 record.');
            Log::error('Error updating Vitamin D3 record', ['user_id' => $request->user()->id, 'vitamin_d3_id' => $vitaminD3->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }

    }

    public function destroy(VitaminD3 $vitaminD3)
    {
        $user = auth()->user();

        if ($vitaminD3->medicalFile->user_id !== $user->id) {
            Inertia::flash('error', 'Unauthorized to delete this Vitamin D3 record.');

            return back();
        }

        try {
            $vitaminD3->delete();

            Inertia::flash('success', 'Vitamin D3 record deleted successfully.');

            return to_route('vitamin-d3.index');
        } catch (Exception $e) {
            Inertia::flash('error', 'An error occurred while deleting the Vitamin D3 record.');
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
