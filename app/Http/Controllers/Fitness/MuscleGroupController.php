<?php

namespace App\Http\Controllers\Fitness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fitness\StoreMuscleGroupRequest;
use App\Http\Requests\Fitness\UpdateMuscleGroupRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Fitness\MuscleGroupServiceInterface;
use App\Models\Fitness\MuscleGroup;
use Exception;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Log;

use function in_array;

class MuscleGroupController extends Controller
{
    public function __construct(private MuscleGroupServiceInterface $muscleGroupService) {}

    public function index(QueryRequest $request): Response
    {
        syncLangFiles('muscle_group_pages');

        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sortBy = $validated['sort_by'] ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $allowedSorts = ['id', 'name', 'exercises_count', 'created_at', 'updated_at'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $muscleGroups = $this->muscleGroupService->getMuscleGroupsData(
            perPage: $perPage,
            sortBy: $sortBy,
            sortDir: $sortDir,
            search: $search,
            filters: $filters,
        );

        return Inertia::render('fitness/muscle-group/index', [
            'muscleGroups' => $muscleGroups,
        ]);
    }

    public function create(): Response
    {
        syncLangFiles('muscle_group_pages');

        return Inertia::render('fitness/muscle-group/create');
    }

    public function store(StoreMuscleGroupRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        try {
            $user->muscleGroups()->create(['name' => $validated['name']]);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.muscle_group.store_successfully')]);

            return to_route('muscle-groups.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.store_failed')]);
            Log::error('Error creating muscle group', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function edit(MuscleGroup $muscleGroup): RedirectResponse|Response
    {
        syncLangFiles('muscle_group_pages');

        $user = auth()->user();

        if ($muscleGroup->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.edit_unauthorized')]);

            return back();
        }

        return Inertia::render('fitness/muscle-group/edit', [
            'muscleGroup' => $muscleGroup,
        ]);
    }

    public function update(UpdateMuscleGroupRequest $request, MuscleGroup $muscleGroup): RedirectResponse
    {
        $user = $request->user();

        if ($muscleGroup->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.update_unauthorized')]);

            return back();
        }

        try {
            $muscleGroup->update(['name' => $request->validated()['name']]);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.muscle_group.update_successfully')]);

            return to_route('muscle-groups.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.update_failed')]);
            Log::error('Error updating muscle group', [
                'user_id' => $user->id,
                'muscle_group_id' => $muscleGroup->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput();
        }
    }

    public function destroy(MuscleGroup $muscleGroup): RedirectResponse
    {
        $user = auth()->user();

        if ($muscleGroup->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.destroy_unauthorized')]);

            return back();
        }

        try {
            $muscleGroup->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.muscle_group.destroy_successfully')]);

            return to_route('muscle-groups.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.destroy_failed')]);
            Log::error('Error deleting muscle group', [
                'user_id' => $user->id,
                'muscle_group_id' => $muscleGroup->id,
                'error' => $e->getMessage(),
            ]);

            return back();
        }
    }
}
