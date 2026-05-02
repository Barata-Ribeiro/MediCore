<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\UricAcidRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\UricAcidServiceInterface;
use App\Models\Exams\UricAcid;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

use function in_array;

class UricAcidController extends Controller
{
    public function __construct(private UricAcidServiceInterface $uricAcidService) {}

    public function index(QueryRequest $request)
    {
        syncLangFiles('uric_acid_pages');

        [$uricAcids, $chartData] = $this->uricAcidPageAndChartData($request);

        return Inertia::render('exams/uric-acid/index', [
            'uricAcids' => $uricAcids,
            'chartData' => $chartData,
        ]);
    }

    public function create()
    {
        syncLangFiles('uric_acid_pages');

        return Inertia::render('exams/uric-acid/create');
    }

    public function store(UricAcidRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->medicalFile->uricAcids()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.uric_acid.store_successfully')]);

            return to_route('uric-acid.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.uric_acid.store_failed')]);
            Log::error('Error creating Uric Acid record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(UricAcid $uricAcid)
    {
        syncLangFiles('uric_acid_pages');

        return Inertia::render('exams/uric-acid/edit', [
            'uricAcid' => $uricAcid,
        ]);
    }

    public function update(UricAcidRequest $request, UricAcid $uricAcid)
    {
        $user = $request->user();

        if ($uricAcid->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.uric_acid.update_failed')]);
            Log::warning('Unauthorized attempt to update Uric Acid record', ['user_id' => $user->id, 'uric_acid_id' => $uricAcid->id]);

            return back()->withInput();
        }

        $validated = $request->validated();

        try {
            $uricAcid->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.uric_acid.update_successfully')]);

            return to_route('uric-acid.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.uric_acid.update_failed')]);
            Log::error('Error updating Uric Acid record', ['user_id' => $request->user()->id, 'uric_acid_id' => $uricAcid->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function destroy(UricAcid $uricAcid)
    {
        $user = request()->user();

        if ($uricAcid->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.uric_acid.destroy_unauthorized')]);
            Log::warning('Unauthorized attempt to delete Uric Acid record', ['user_id' => $user->id, 'uric_acid_id' => $uricAcid->id]);

            return back();
        }

        try {
            $uricAcid->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.uric_acid.destroy_successfully')]);

            return to_route('uric-acid.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.uric_acid.destroy_failed')]);
            Log::error('Error deleting Uric Acid record', ['user_id' => $user->id, 'uric_acid_id' => $uricAcid->id, 'error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Validate request query inputs, apply sort/filter defaults, and fetch
     * paginated uric acid results plus chart data.
     *
     * @return array{
     *     0: LengthAwarePaginator,
     *     1: array<string, mixed>
     * }
     */
    private function uricAcidPageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'uric_acid_level', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->uricAcidService->getUricAcidsData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
