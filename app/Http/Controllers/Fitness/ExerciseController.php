<?php

namespace App\Http\Controllers\Fitness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fitness\StoreExerciseRequest;
use App\Http\Requests\Fitness\UpdateExerciseRequest;
use App\Http\Requests\QueryRequest;
use App\Interfaces\Fitness\ExerciseServiceInterface;
use App\Models\Fitness\Exercise;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Log;

class ExerciseController extends Controller
{
    public function __construct(private ExerciseServiceInterface $exerciseService) {}

    public function index(QueryRequest $request): Response
    {
        syncLangFiles(['exercise_pages', 'muscle_group_pages']);

        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sorting = $validated['sorting'] ?? [];
        $search = trim($validated['search'] ?? '');
        $filters = $validated['filters'] ?? [];

        $exercises = $this->exerciseService->getExercisesData(
            perPage: $perPage,
            sorting: $sorting,
            search: $search,
            filters: $filters,
        );

        return Inertia::render('fitness/exercise/index', [
            'exercises' => $exercises,
        ]);
    }

    public function create(): Response
    {
        syncLangFiles(['exercise_pages', 'muscle_group_pages']);

        $user = auth()->user();

        return Inertia::render('fitness/exercise/create', [
            'muscleGroups' => $user->muscleGroups()->select(['id', 'name'])->orderBy('name')->get(),
        ]);
    }

    public function edit(Exercise $exercise): RedirectResponse|Response
    {
        syncLangFiles(['exercise_pages', 'muscle_group_pages']);

        $user = auth()->user();

        if ($exercise->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.edit_unauthorized')]);

            return back();
        }

        return Inertia::render('fitness/exercise/edit', [
            'exercise' => $exercise->load(['muscleGroups' => fn ($query) => $query->select(['muscle_groups.id', 'name'])->orderBy('name')]),
            'muscleGroups' => $user->muscleGroups()->select(['id', 'name'])->orderBy('name')->get(),
        ]);
    }

    public function store(StoreExerciseRequest $request): RedirectResponse|JsonResponse
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

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                report($e);

                return response()->json(['message' => __('flash.exercise.store_failed')], 500);
            }

            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.store_failed')]);
            Log::error('Error creating exercise', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json(['exercise' => $exercise->load('muscleGroups'), 'message' => __('flash.exercise.store_successfully')], 201);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exercise.store_successfully')]);

        return to_route('exercises.index');
    }

    public function update(UpdateExerciseRequest $request, Exercise $exercise): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if ($exercise->user_id !== $user->id) {
            abort_if($request->expectsJson(), 403);

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

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                report($e);

                return response()->json(['message' => __('flash.exercise.update_failed')], 500);
            }

            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.exercise.update_failed')]);
            Log::error('Error updating exercise', [
                'user_id' => $user->id,
                'exercise_id' => $exercise->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json(['exercise' => $exercise->load('muscleGroups'), 'message' => __('flash.exercise.update_successfully')], 200);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.exercise.update_successfully')]);

        return to_route('exercises.index');
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
