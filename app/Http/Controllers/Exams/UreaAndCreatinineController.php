<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\UreaAndCreatinineRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\UreaAndCreatinineServiceInterface;
use App\Models\Exams\UreaAndCreatinine;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

use function in_array;

class UreaAndCreatinineController extends Controller
{
    public function __construct(private UreaAndCreatinineServiceInterface $ureaAndCreatinineService) {}

    public function index(QueryRequest $request)
    {
        [$ureaAndCreatinines, $chartData] = $this->ureaAndCreatininePageAndChartData($request);

        return Inertia::render('exams/urea-and-creatinine/index', [
            'ureaAndCreatinines' => $ureaAndCreatinines,
            'chartData' => $chartData,
        ]);
    }

    public function create()
    {
        return Inertia::render('exams/urea-and-creatinine/create');
    }

    public function store(UreaAndCreatinineRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->medicalFile->ureaAndCreatinines()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.urea_and_creatinine.store_successfully')]);

            return to_route('urea-and-creatinine.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.urea_and_creatinine.store_failed')]);
            Log::error('Error creating Urea and Creatinine record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(UreaAndCreatinine $ureaAndCreatinine)
    {
        return Inertia::render('exams/urea-and-creatinine/edit', [
            'ureaAndCreatinine' => $ureaAndCreatinine,
        ]);
    }

    public function update(UreaAndCreatinineRequest $request, UreaAndCreatinine $ureaAndCreatinine)
    {
        $user = $request->user();

        if ($ureaAndCreatinine->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.urea_and_creatinine.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            $ureaAndCreatinine->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.urea_and_creatinine.update_successfully')]);

            return to_route('urea-and-creatinine.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.urea_and_creatinine.update_failed')]);
            Log::error('Error updating Urea and Creatinine record', ['user_id' => $request->user()->id, 'record_id' => $ureaAndCreatinine->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function destroy(UreaAndCreatinine $ureaAndCreatinine)
    {
        $user = request()->user();

        if ($ureaAndCreatinine->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.urea_and_creatinine.destroy_unauthorized')]);

            return back();
        }

        try {
            $ureaAndCreatinine->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.urea_and_creatinine.destroy_successfully')]);

            return to_route('urea-and-creatinine.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.urea_and_creatinine.destroy_failed')]);
            Log::error('Error deleting Urea and Creatinine record', ['user_id' => $user->id, 'record_id' => $ureaAndCreatinine->id, 'error' => $e->getMessage()]);

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
    private function ureaAndCreatininePageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'urea_level', 'creatinine_level', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->ureaAndCreatinineService->getUreaAndCreatininesData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
