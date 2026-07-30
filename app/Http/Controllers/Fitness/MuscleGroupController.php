<?php

namespace App\Http\Controllers\Fitness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fitness\StoreMuscleGroupRequest;
use App\Http\Requests\Fitness\UpdateMuscleGroupRequest;
use App\Models\Fitness\MuscleGroup;
use Exception;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Log;

class MuscleGroupController extends Controller
{
    public function index(): Response
    {
        syncLangFiles('muscle_group_pages');

        $muscleGroups = MuscleGroup::query()
            ->whereBelongsTo(auth()->user())
            ->withCount('exercises')
            ->orderBy('name')
            ->get();

        return Inertia::render('fitness/muscle-group/index', [
            'muscleGroups' => $muscleGroups,
        ]);
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
