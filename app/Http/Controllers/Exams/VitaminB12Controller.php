<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\VitaminB12Request;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\VitaminB12ServiceInterface;
use App\Models\Exams\VitaminB12;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

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

    public function create()
    {
        return Inertia::render('exams/vitamin-b12/create');
    }

    public function store(VitaminB12Request $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {

            $user->medicalFile->vitaminB12s()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Vitamin B12 record created successfully.']);

            return to_route('vitamin-b12.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'An error occurred while creating the Vitamin B12 record.']);
            Log::error('Error creating Vitamin B12 record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(VitaminB12 $vitaminB12)
    {
        return Inertia::render('exams/vitamin-b12/edit', [
            'vitaminB12' => $vitaminB12,
        ]);
    }

    public function update(VitaminB12Request $request, VitaminB12 $vitaminB12)
    {
        $user = $request->user();

        if ($vitaminB12->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'Unauthorized to update this Vitamin B12 record.']);

            return back();
        }

        $validated = $request->validated();

        try {
            $vitaminB12->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Vitamin B12 record updated successfully.']);

            return to_route('vitamin-b12.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'An error occurred while updating the Vitamin B12 record.']);
            Log::error('Error updating Vitamin B12 record', ['user_id' => $request->user()->id, 'vitamin_b12_id' => $vitaminB12->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }

    }

    public function destroy(VitaminB12 $vitaminB12)
    {
        $user = auth()->user();

        if ($vitaminB12->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'Unauthorized to delete this Vitamin B12 record.']);

            return back();
        }

        try {
            $vitaminB12->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Vitamin B12 record deleted successfully.']);

            return to_route('vitamin-b12.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'An error occurred while deleting the Vitamin B12 record.']);
            Log::error('Error deleting Vitamin B12 record', ['user_id' => $user->id, 'vitamin_b12_id' => $vitaminB12->id, 'error' => $e->getMessage()]);

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
