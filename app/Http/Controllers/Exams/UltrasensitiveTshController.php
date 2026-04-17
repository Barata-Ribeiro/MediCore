<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\UltrasensitiveTshRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\UltrasensitiveTshServiceInterface;
use App\Models\Exams\UltrasensitiveTsh;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

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

    public function create()
    {
        return Inertia::render('exams/ultrasensitive-tsh/create');
    }

    public function store(UltrasensitiveTshRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {

            $user->medicalFile->ultrasensitiveTshs()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Ultrasensitive TSH record created successfully.']);

            return to_route('ultrasensitive-tsh.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'An error occurred while creating the Ultrasensitive TSH record.']);
            Log::error('Error creating Ultrasensitive TSH record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(UltrasensitiveTsh $ultrasensitiveTsh)
    {
        return Inertia::render('exams/ultrasensitive-tsh/edit', [
            'ultrasensitiveTsh' => $ultrasensitiveTsh,
        ]);
    }

    public function update(UltrasensitiveTshRequest $request, UltrasensitiveTsh $ultrasensitiveTsh)
    {
        $validated = $request->validated();

        if ($ultrasensitiveTsh->medicalFile->user_id !== $request->user()->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'Unauthorized to update this Ultrasensitive TSH record.']);
            Log::warning('Unauthorized update attempt on Ultrasensitive TSH record', ['user_id' => $request->user()->id, 'record_id' => $ultrasensitiveTsh->id]);

            return back();
        }

        try {
            $ultrasensitiveTsh->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Ultrasensitive TSH record updated successfully.']);

            return to_route('ultrasensitive-tsh.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'An error occurred while updating the Ultrasensitive TSH record.']);
            Log::error('Error updating Ultrasensitive TSH record', ['user_id' => $request->user()->id, 'ultrasensitive_tsh_id' => $ultrasensitiveTsh->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function destroy(UltrasensitiveTsh $ultrasensitiveTsh)
    {
        if ($ultrasensitiveTsh->medicalFile->user_id !== request()->user()->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'Unauthorized to delete this Ultrasensitive TSH record.']);
            Log::warning('Unauthorized delete attempt on Ultrasensitive TSH record', ['user_id' => request()->user()->id, 'record_id' => $ultrasensitiveTsh->id]);

            return back();
        }

        try {
            $ultrasensitiveTsh->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Ultrasensitive TSH record deleted successfully.']);

            return to_route('ultrasensitive-tsh.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'An error occurred while deleting the Ultrasensitive TSH record.']);
            Log::error('Error deleting Ultrasensitive TSH record', ['user_id' => request()->user()->id, 'ultrasensitive_tsh_id' => $ultrasensitiveTsh->id, 'error' => $e->getMessage()]);

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
    private function ultrasensitiveTshPageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'tsh_level', 'report_date', 'created_at'];
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
