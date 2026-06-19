<?php

namespace App\Http\Controllers\Fitness;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fitness\StoreWorkoutRequest;
use App\Http\Requests\Fitness\UpdateWorkoutRequest;
use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use App\Models\Fitness\WorkoutExercise;
use App\Models\Fitness\WorkoutSection;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Log;
use Throwable;

use function in_array;

class WorkoutController extends Controller
{
    public function index(): Response
    {
        $workouts = Workout::query()
            ->whereBelongsTo(auth()->user())
            ->with([
                'sections' => fn ($query) => $query->select(['id', 'workout_id', 'name', 'order'])->orderBy('order'),
                'sections.exercises' => fn ($query) => $query
                    ->select([
                        'id',
                        'workout_section_id',
                        'exercise_id',
                        'muscle_group_id',
                        'code',
                        'order',
                        'sets',
                        'reps',
                        'load',
                        'load_unit',
                        'rest_seconds',
                        'notes',
                    ])
                    ->orderBy('order'),
            ])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('fitness/workout/index', [
            'workouts' => $workouts,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('fitness/workout/create', [
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function store(StoreWorkoutRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $user): void {
                /** @var Workout $workout */
                $workout = $user->workouts()->create($this->workoutAttributes($validated));

                $this->syncSections($workout, $validated['sections'] ?? []);
            });

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.workout.store_successfully')]);

            return to_route('workouts.index');
        } catch (Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.workout.store_failed')]);
            Log::error('Error creating workout', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->withInput();
        }
    }

    public function show(Workout $workout): Response|RedirectResponse
    {
        if ($workout->user_id !== auth()->id()) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.workout.show_unauthorized')]);

            return back();
        }

        $workout->load([
            'sections' => fn ($query) => $query->orderBy('order'),
            'sections.exercises' => fn ($query) => $query->orderBy('order'),
        ]);

        return Inertia::render('fitness/workout/show', [
            'workout' => $workout,
        ]);
    }

    public function edit(Workout $workout): Response|RedirectResponse
    {
        if ($workout->user_id !== auth()->id()) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.workout.edit_unauthorized')]);

            return back();
        }

        $workout->load([
            'sections' => fn ($query) => $query->orderBy('order'),
            'sections.exercises' => fn ($query) => $query->orderBy('order'),
        ]);

        return Inertia::render('fitness/workout/edit', [
            'workout' => $workout,
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function update(UpdateWorkoutRequest $request, Workout $workout): RedirectResponse
    {
        $user = $request->user();

        if ($workout->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.workout.update_unauthorized')]);

            return back();
        }

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $workout): void {
                $workout->update($this->workoutAttributes($validated));
                $this->syncSections($workout, $validated['sections'] ?? []);
            });

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.workout.update_successfully')]);

            return to_route('workouts.index');
        } catch (Throwable $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.workout.update_failed')]);
            Log::error('Error updating workout', [
                'user_id' => $user->id,
                'workout_id' => $workout->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput();
        }
    }

    public function destroy(Workout $workout): RedirectResponse
    {
        $user = auth()->user();

        if ($workout->user_id !== $user->id) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.workout.destroy_unauthorized')]);

            return back();
        }

        try {
            $workout->delete();

            Inertia::flash('toast', ['type' => 'success', 'message' => __('flash.workout.destroy_successfully')]);

            return to_route('workouts.index');
        } catch (Throwable $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('flash.workout.destroy_failed')]);
            Log::error('Error deleting workout', [
                'user_id' => $user->id,
                'workout_id' => $workout->id,
                'error' => $e->getMessage(),
            ]);

            return back();
        }
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function workoutAttributes(array $validated): array
    {
        return Arr::only($validated, [
            'filled_at',
            'next_change_at',
            'goal',
            'method',
            'rest_between_sets',
            'rest_between_exercises',
            'is_active',
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $sections
     */
    private function syncSections(Workout $workout, array $sections): void
    {
        /** @var Collection<int, WorkoutSection> $existingSections */
        $existingSections = $workout->sections()->with('exercises')->get();
        $existingSectionIds = $existingSections->pluck('id')->all();

        $keptSectionIds = [];

        foreach ($sections as $sectionPayload) {
            $sectionId = isset($sectionPayload['id']) ? (int) $sectionPayload['id'] : null;
            $sectionAttributes = Arr::only($sectionPayload, ['name', 'order']);

            if ($sectionId !== null && in_array($sectionId, $existingSectionIds, true)) {
                /** @var WorkoutSection|null $section */
                $section = $existingSections->firstWhere('id', $sectionId);
                if ($section === null) {
                    continue;
                }

                $section->update($sectionAttributes);
            } else {
                $section = $workout->sections()->create($sectionAttributes);
            }

            $keptSectionIds[] = $section->id;
            $this->syncExercises($section, $sectionPayload['exercises'] ?? []);
        }

        $workout->sections()->whereNotIn('id', $keptSectionIds)->delete();
    }

    /**
     * @param  array<int, array<string, mixed>>  $exercises
     */
    private function syncExercises(WorkoutSection $section, array $exercises): void
    {
        /** @var Collection<int, WorkoutExercise> $existingExercises */
        $existingExercises = $section->exercises()->get();
        $existingExerciseIds = $existingExercises->pluck('id')->all();

        $keptExerciseIds = [];

        foreach ($exercises as $exercisePayload) {
            $exerciseId = isset($exercisePayload['id']) ? (int) $exercisePayload['id'] : null;
            $exerciseAttributes = Arr::only($exercisePayload, [
                'exercise_id',
                'muscle_group_id',
                'code',
                'order',
                'sets',
                'reps',
                'load',
                'load_unit',
                'rest_seconds',
                'notes',
            ]);

            if ($exerciseId !== null && in_array($exerciseId, $existingExerciseIds, true)) {
                /** @var WorkoutExercise|null $exercise */
                $exercise = $existingExercises->firstWhere('id', $exerciseId);
                if ($exercise === null) {
                    continue;
                }

                $exercise->update($exerciseAttributes);
            } else {
                $exercise = $section->exercises()->create($exerciseAttributes);
            }

            $keptExerciseIds[] = $exercise->id;
        }

        $section->exercises()->whereNotIn('id', $keptExerciseIds)->delete();
    }

    /**
     * @return array<string, Collection<int, Exercise>|Collection<int, MuscleGroup>>
     */
    private function formOptions(): array
    {
        return [
            'exercises' => Exercise::query()->select(['id', 'name'])->orderBy('name')->get(),
            'muscleGroups' => MuscleGroup::query()->select(['id', 'name'])->orderBy('name')->get(),
        ];
    }
}
