<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\TgoAndTgpRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\TgoAndTgpServiceInterface;
use App\Models\Exams\TgoAndTgp;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Log;

use function in_array;

class TgoAndTgpController extends Controller
{
    public function __construct(private TgoAndTgpServiceInterface $tgoAndTgpService) {}

    public function index(QueryRequest $request): Response
    {
        syncLangFiles('tgo_and_tgp_pages');

        [$tgoAndTgps, $chartData] = $this->tgoAndTgpPageAndChartData($request);

        return Inertia::render('exams/tgo-and-tgp/index', [
            'tgoAndTgps' => $tgoAndTgps,
            'chartData' => $chartData,
        ]);
    }

    public function create(): Response
    {
        syncLangFiles('tgo_and_tgp_pages');

        return Inertia::render('exams/tgo-and-tgp/create');
    }

    public function store(TgoAndTgpRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->medicalFile->tgoAndTgps()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.tgo_and_tgp.store_successfully')]);

            return to_route('tgo-and-tgp.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.tgo_and_tgp.store_failed')]);
            Log::error('Error creating TGO and TGP record', ['user_id' => $request->user()->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(TgoAndTgp $tgoAndTgp): Response
    {
        abort_unless($tgoAndTgp->medicalFile->user_id === request()->user()->id, 404);

        syncLangFiles('tgo_and_tgp_pages');

        return Inertia::render('exams/tgo-and-tgp/edit', [
            'tgoAndTgp' => $tgoAndTgp,
        ]);
    }

    public function update(TgoAndTgpRequest $request, TgoAndTgp $tgoAndTgp): RedirectResponse
    {
        $user = $request->user();

        if ($tgoAndTgp->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.tgo_and_tgp.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            $tgoAndTgp->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.tgo_and_tgp.update_successfully')]);

            return to_route('tgo-and-tgp.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.tgo_and_tgp.update_failed')]);
            Log::error('Error updating TGO and TGP record', ['user_id' => $request->user()->id, 'record_id' => $tgoAndTgp->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function destroy(TgoAndTgp $tgoAndTgp): RedirectResponse
    {
        $user = request()->user();

        if ($tgoAndTgp->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.tgo_and_tgp.destroy_unauthorized')]);

            return back();
        }

        try {
            $tgoAndTgp->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.tgo_and_tgp.destroy_successfully')]);

            return to_route('tgo-and-tgp.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.tgo_and_tgp.destroy_failed')]);
            Log::error('Error deleting TGO and TGP record', ['user_id' => $user->id, 'record_id' => $tgoAndTgp->id, 'error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Validate request query inputs, apply sort/filter defaults, and fetch
     * paginated TGO and TGP results plus chart data.
     *
     * @return array{
     *     0: LengthAwarePaginator<int, TgoAndTgp>,
     *     1: array<string, mixed>
     * }
     */
    private function tgoAndTgpPageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'tgo_level', 'tgp_level', 'report_date', 'created_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->tgoAndTgpService->getTgoAndTgpsData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters
        );
    }
}
