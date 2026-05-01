<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\LipidProfileRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Exams\LipidProfileServiceInterface;
use App\Models\Exams\LipidProfile;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Log;

use function in_array;

class LipidProfileController extends Controller
{
    public function __construct(private LipidProfileServiceInterface $lipidProfileService) {}

    public function index(QueryRequest $request)
    {
        syncLangFiles('lipid_profile_pages');

        [$lipidProfiles, $chartData] = $this->lipidProfilePageAndChartData($request);

        return Inertia::render('exams/lipid-profile/index', [
            'lipidProfiles' => $lipidProfiles,
            'chartData' => $chartData,
        ]);
    }

    public function create()
    {
        syncLangFiles('lipid_profile_pages');

        return Inertia::render('exams/lipid-profile/create');
    }

    public function store(LipidProfileRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        try {
            $user->medicalFile->lipidProfiles()->create($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.lipid_profile.store_successfully')]);

            return to_route('lipid-profile.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.lipid_profile.store_failed')]);
            Log::error('Error creating lipid profile', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(LipidProfile $lipidProfile)
    {
        syncLangFiles('lipid_profile_pages');

        return Inertia::render('exams/lipid-profile/edit', [
            'lipidProfile' => $lipidProfile,
        ]);
    }

    public function update(LipidProfileRequest $request, LipidProfile $lipidProfile)
    {
        $user = $request->user();

        if ($lipidProfile->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.lipid_profile.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            $lipidProfile->update($validated);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.lipid_profile.update_successfully')]);

            return to_route('lipid-profile.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.lipid_profile.update_failed')]);
            Log::error('Error updating lipid profile', ['user_id' => $user->id, 'record_id' => $lipidProfile->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function destroy(LipidProfile $lipidProfile)
    {
        $user = request()->user();

        if ($lipidProfile->medicalFile->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.lipid_profile.destroy_unauthorized')]);

            return back();
        }

        try {
            $lipidProfile->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exams.lipid_profile.destroy_successfully')]);

            return to_route('lipid-profile.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exams.lipid_profile.destroy_failed')]);
            Log::error('Error deleting lipid profile', ['user_id' => $user->id, 'record_id' => $lipidProfile->id, 'error' => $e->getMessage()]);

            return back();
        }
    }

    /**
     * Validate request query inputs, apply sort/filter defaults, and fetch
     * paginated lipid profile results plus chart data.
     *
     * @return array{
     *     0: LengthAwarePaginator,
     *     1: array<string, mixed>
     * }
     */
    private function lipidProfilePageAndChartData(QueryRequest $request): array
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'report_date', 'total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides', 'created_at'];

        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        return $this->lipidProfileService->getLipidProfileData($perPage, $sortBy, $sortDir, $search, $filters);
    }
}
