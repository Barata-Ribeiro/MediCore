<?php

namespace App\Http\Controllers\Fitness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fitness\StoreExerciseRequest;
use App\Http\Requests\Fitness\UpdateExerciseRequest;
use App\Models\Fitness\Exercise;
use Exception;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Log;

class ExerciseController extends Controller
{
    public function index(): Response
    {
        syncLangFiles('exercise_pages');

        $user = auth()->user();

        $exercises = Exercise::query()
            ->whereBelongsTo($user)
            ->with(['muscleGroups' => fn ($query) => $query->select(['muscle_groups.id', 'name'])->orderBy('name')])
            ->orderBy('name')
            ->get();

        return Inertia::render('fitness/exercise/index', [
            'exercises' => $exercises,
            'muscleGroups' => $user->muscleGroups()->select(['id', 'name'])->orderBy('name')->get(),
        ]);
    }

    public function store(StoreExerciseRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        try {
            $exercise = $user->exercises()->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
            ]);

            $exercise->muscleGroups()->sync($validated['muscle_group_ids'] ?? []);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exercise.store_successfully')]);

            return to_route('exercises.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.store_failed')]);
            Log::error('Error creating exercise', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function update(UpdateExerciseRequest $request, Exercise $exercise): RedirectResponse
    {
        $user = $request->user();

        if ($exercise->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            $exercise->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
            ]);
            $exercise->muscleGroups()->sync($validated['muscle_group_ids'] ?? []);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exercise.update_successfully')]);

            return to_route('exercises.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.update_failed')]);
            Log::error('Error updating exercise', [
                'user_id' => $user->id,
                'exercise_id' => $exercise->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput();
        }
    }

    public function destroy(Exercise $exercise): RedirectResponse
    {
        $user = auth()->user();

        if ($exercise->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.destroy_unauthorized')]);

            return back();
        }

        try {
            $exercise->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exercise.destroy_successfully')]);

            return to_route('exercises.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.destroy_failed')]);
            Log::error('Error deleting exercise', [
                'user_id' => $user->id,
                'exercise_id' => $exercise->id,
                'error' => $e->getMessage(),
            ]);

            return back();
        }
    }
}
