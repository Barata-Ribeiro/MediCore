<?php

namespace App\Http\Controllers\Fitness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fitness\StoreMuscleGroupRequest;
use App\Http\Requests\Fitness\UpdateMuscleGroupRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Fitness\MuscleGroupServiceInterface;
use App\Models\Fitness\MuscleGroup;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Log;

class MuscleGroupController extends Controller
{
    public function __construct(private MuscleGroupServiceInterface $muscleGroupService) {}

    public function index(QueryRequest $request): Response
    {
        syncLangFiles('muscle_group_pages');

        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sorting = $validated['sorting'] ?? [];
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $muscleGroups = $this->muscleGroupService->getMuscleGroupsData(
            perPage: $perPage,
            sorting: $sorting,
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

    public function store(StoreMuscleGroupRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        try {
            $muscleGroup = $user->muscleGroups()->create(['name' => $validated['name']]);

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                report($e);

                return response()->json(['message' => __('flash.muscle_group.store_failed')], 500);
            }

            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.store_failed')]);
            Log::error('Error creating muscle group', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json(['muscleGroup' => $muscleGroup, 'message' => __('flash.muscle_group.store_successfully')], 201);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.muscle_group.store_successfully')]);

        return to_route('muscle-groups.index');
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

    public function update(UpdateMuscleGroupRequest $request, MuscleGroup $muscleGroup): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if ($muscleGroup->user_id !== $user->id) {
            abort_if($request->expectsJson(), 403);

            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.update_unauthorized')]);

            return back();
        }

        try {
            $muscleGroup->update(['name' => $request->validated()['name']]);

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                report($e);

                return response()->json(['message' => __('flash.muscle_group.update_failed')], 500);
            }

            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.muscle_group.update_failed')]);
            Log::error('Error updating muscle group', [
                'user_id' => $user->id,
                'muscle_group_id' => $muscleGroup->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json(['muscleGroup' => $muscleGroup, 'message' => __('flash.muscle_group.update_successfully')], 200);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.muscle_group.update_successfully')]);

        return to_route('muscle-groups.index');
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
