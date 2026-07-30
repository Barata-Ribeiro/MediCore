import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyDescription, EmptyHeader, EmptyTitle } from '@/components/ui/empty';
import { Field, FieldDescription, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { create, index, store, update } from '@/routes/workouts';
import type { WorkoutFormOptions, WorkoutResource } from '@/types/application/fitness/workout';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { Activity, Fragment } from 'react';

type Props = {
    workout?: WorkoutResource;
    formOptions: WorkoutFormOptions;
};

type WorkoutExerciseInput = {
    id?: number;
    exercise_id: number | '';
    muscle_group_id: number | '';
    code: string;
    order: number;
    sets: number;
    reps: string;
    load: string;
    load_unit: string;
    rest_seconds: string;
    notes: string;
};

type WorkoutSectionInput = {
    id?: number;
    name: string;
    order: number;
    exercises: WorkoutExerciseInput[];
};

type WorkoutFormData = {
    filled_at: string;
    next_change_at: string;
    goal: string;
    method: string;
    rest_between_sets: string;
    rest_between_exercises: string;
    is_active: boolean;
    sections: WorkoutSectionInput[];
};

function createExerciseInput(order: number): WorkoutExerciseInput {
    return {
        exercise_id: '',
        muscle_group_id: '',
        code: '',
        order,
        sets: 3,
        reps: '8-12',
        load: '',
        load_unit: 'kg',
        rest_seconds: '',
        notes: '',
    };
}

function createSectionInput(order: number): WorkoutSectionInput {
    return {
        name: '',
        order,
        exercises: [createExerciseInput(1)],
    };
}

export default function WorkoutRegistryForm({ workout, formOptions }: Readonly<Props>) {
    const { __ } = lang();
    const isEditMode = workout !== undefined;

    const initialSections: WorkoutSectionInput[] = workout?.sections.map<WorkoutSectionInput>((section) => ({
        id: section.id,
        name: section.name,
        order: section.order,
        exercises: section.exercises.map<WorkoutExerciseInput>((exercise) => ({
            id: exercise.id,
            exercise_id: exercise.exercise_id,
            muscle_group_id: exercise.muscle_group_id ?? '',
            code: exercise.code ?? '',
            order: exercise.order,
            sets: exercise.sets,
            reps: exercise.reps,
            load: exercise.load === null ? '' : String(exercise.load),
            load_unit: exercise.load_unit,
            rest_seconds: exercise.rest_seconds === null ? '' : String(exercise.rest_seconds),
            notes: exercise.notes ?? '',
        })),
    })) ?? [createSectionInput(1)];

    const { data, setData, post, put, processing, errors, transform } = useForm<WorkoutFormData>({
        filled_at: workout?.filled_at ?? '',
        next_change_at: workout?.next_change_at ?? '',
        goal: workout?.goal ?? '',
        method: workout?.method ?? '',
        rest_between_sets:
            workout?.rest_between_sets === null || workout?.rest_between_sets === undefined
                ? ''
                : String(workout.rest_between_sets),
        rest_between_exercises:
            workout?.rest_between_exercises === null || workout?.rest_between_exercises === undefined
                ? ''
                : String(workout.rest_between_exercises),
        is_active: workout?.is_active ?? true,
        sections: initialSections,
    });

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        transform((values) => ({
            ...values,
            filled_at: values.filled_at || null,
            next_change_at: values.next_change_at || null,
            goal: values.goal || null,
            method: values.method || null,
            rest_between_sets: values.rest_between_sets === '' ? null : Number(values.rest_between_sets),
            rest_between_exercises: values.rest_between_exercises === '' ? null : Number(values.rest_between_exercises),
            sections: values.sections.map((section) => ({
                id: section.id,
                name: section.name,
                order: section.order,
                exercises: section.exercises.map((exercise) => ({
                    id: exercise.id,
                    exercise_id: Number(exercise.exercise_id),
                    muscle_group_id: exercise.muscle_group_id === '' ? null : Number(exercise.muscle_group_id),
                    code: exercise.code || null,
                    order: exercise.order,
                    sets: exercise.sets,
                    reps: exercise.reps,
                    load: exercise.load === '' ? null : Number(exercise.load),
                    load_unit: exercise.load_unit,
                    rest_seconds: exercise.rest_seconds === '' ? null : Number(exercise.rest_seconds),
                    notes: exercise.notes || null,
                })),
            })),
        }));

        if (isEditMode && workout) {
            put(update(workout.id).url, { preserveScroll: true });

            return;
        }

        post(store().url, { preserveScroll: true });
    };

    const replaceSections = (sections: WorkoutSectionInput[]) => {
        setData(
            'sections',
            sections.map((section, sectionIndex) => ({
                ...section,
                order: sectionIndex + 1,
                exercises: section.exercises.map((exercise, exerciseIndex) => ({
                    ...exercise,
                    order: exerciseIndex + 1,
                })),
            })),
        );
    };

    const updateSection = (sectionIndex: number, patch: Partial<WorkoutSectionInput>) => {
        replaceSections(
            data.sections.map((section, index) => {
                if (index !== sectionIndex) {
                    return section;
                }

                return {
                    id: section.id,
                    name: patch.name ?? section.name,
                    order: patch.order ?? section.order,
                    exercises: patch.exercises ?? section.exercises,
                };
            }),
        );
    };

    const addSection = () => {
        replaceSections([...data.sections, createSectionInput(data.sections.length + 1)]);
    };

    const removeSection = (sectionIndex: number) => {
        replaceSections(data.sections.filter((_, index) => index !== sectionIndex));
    };

    const updateExercise = (sectionIndex: number, exerciseIndex: number, patch: Partial<WorkoutExerciseInput>) => {
        replaceSections(
            data.sections.map((section, currentSectionIndex) => {
                if (currentSectionIndex !== sectionIndex) {
                    return section;
                }

                return {
                    ...section,
                    exercises: section.exercises.map((exercise, currentExerciseIndex) => {
                        if (currentExerciseIndex !== exerciseIndex) {
                            return exercise;
                        }

                        return {
                            id: exercise.id,
                            exercise_id: patch.exercise_id ?? exercise.exercise_id,
                            muscle_group_id: patch.muscle_group_id ?? exercise.muscle_group_id,
                            code: patch.code ?? exercise.code,
                            order: patch.order ?? exercise.order,
                            sets: patch.sets ?? exercise.sets,
                            reps: patch.reps ?? exercise.reps,
                            load: patch.load ?? exercise.load,
                            load_unit: patch.load_unit ?? exercise.load_unit,
                            rest_seconds: patch.rest_seconds ?? exercise.rest_seconds,
                            notes: patch.notes ?? exercise.notes,
                        };
                    }),
                };
            }),
        );
    };

    const addExercise = (sectionIndex: number) => {
        replaceSections(
            data.sections.map((section, index) => {
                if (index !== sectionIndex) {
                    return section;
                }

                return {
                    ...section,
                    exercises: [...section.exercises, createExerciseInput(section.exercises.length + 1)],
                };
            }),
        );
    };

    const removeExercise = (sectionIndex: number, exerciseIndex: number) => {
        replaceSections(
            data.sections.map((section, index) => {
                if (index !== sectionIndex) {
                    return section;
                }

                return {
                    ...section,
                    exercises: section.exercises.filter(
                        (_, currentExerciseIndex) => currentExerciseIndex !== exerciseIndex,
                    ),
                };
            }),
        );
    };

    const handleExerciseChange = (sectionIndex: number, exerciseIndex: number, exerciseIdRaw: string) => {
        const selectedExerciseId = exerciseIdRaw === '' ? '' : Number(exerciseIdRaw);
        const selectedExercise = formOptions.exercises.find((exercise) => exercise.id === selectedExerciseId);

        const currentMuscleGroupId = data.sections[sectionIndex]?.exercises[exerciseIndex]?.muscle_group_id ?? '';

        const hasSelectedMuscleGroup =
            currentMuscleGroupId !== '' &&
            selectedExercise?.muscle_groups.some((muscleGroup) => muscleGroup.id === Number(currentMuscleGroupId));

        updateExercise(sectionIndex, exerciseIndex, {
            exercise_id: selectedExerciseId,
            muscle_group_id: hasSelectedMuscleGroup ? currentMuscleGroupId : '',
        });
    };

    const availableMuscleGroups = (exerciseId: number | '') => {
        if (exerciseId === '') {
            return [];
        }

        return formOptions.exercises.find((exercise) => exercise.id === Number(exerciseId))?.muscle_groups ?? [];
    };

    return (
        <form onSubmit={submit} className="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>{__('workout_pages.form.identity_title')}</CardTitle>
                    <CardDescription>{__('workout_pages.form.identity_description')}</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-4 md:grid-cols-2">
                        <Field data-invalid={!!errors.goal}>
                            <FieldLabel htmlFor="goal">{__('workout_pages.form.goal')}</FieldLabel>
                            <Input
                                id="goal"
                                value={data.goal}
                                onChange={(event) => setData('goal', event.target.value)}
                                placeholder={__('workout_pages.form.goal_placeholder')}
                                aria-invalid={!!errors.goal}
                            />
                            <InputError message={errors.goal} />
                        </Field>

                        <Field data-invalid={!!errors.method}>
                            <FieldLabel htmlFor="method">{__('workout_pages.form.method')}</FieldLabel>
                            <Input
                                id="method"
                                value={data.method}
                                onChange={(event) => setData('method', event.target.value)}
                                placeholder={__('workout_pages.form.method_placeholder')}
                                aria-invalid={!!errors.method}
                            />
                            <InputError message={errors.method} />
                        </Field>

                        <Field data-invalid={!!errors.filled_at}>
                            <FieldLabel htmlFor="filled_at">{__('workout_pages.form.filled_at')}</FieldLabel>
                            <Input
                                id="filled_at"
                                type="date"
                                value={data.filled_at}
                                onChange={(event) => setData('filled_at', event.target.value)}
                                aria-invalid={!!errors.filled_at}
                            />
                            <InputError message={errors.filled_at} />
                        </Field>

                        <Field data-invalid={!!errors.next_change_at}>
                            <FieldLabel htmlFor="next_change_at">{__('workout_pages.form.next_change_at')}</FieldLabel>
                            <Input
                                id="next_change_at"
                                type="date"
                                value={data.next_change_at}
                                onChange={(event) => setData('next_change_at', event.target.value)}
                                aria-invalid={!!errors.next_change_at}
                            />
                            <InputError message={errors.next_change_at} />
                        </Field>

                        <Field data-invalid={!!errors.rest_between_sets}>
                            <FieldLabel htmlFor="rest_between_sets">
                                {__('workout_pages.form.rest_between_sets')}
                            </FieldLabel>
                            <Input
                                id="rest_between_sets"
                                type="number"
                                min={0}
                                value={data.rest_between_sets}
                                onChange={(event) => setData('rest_between_sets', event.target.value)}
                                placeholder={__('workout_pages.form.rest_between_sets_placeholder')}
                                aria-invalid={!!errors.rest_between_sets}
                            />
                            <InputError message={errors.rest_between_sets} />
                        </Field>

                        <Field data-invalid={!!errors.rest_between_exercises}>
                            <FieldLabel htmlFor="rest_between_exercises">
                                {__('workout_pages.form.rest_between_exercises')}
                            </FieldLabel>
                            <Input
                                id="rest_between_exercises"
                                type="number"
                                min={0}
                                value={data.rest_between_exercises}
                                onChange={(event) => setData('rest_between_exercises', event.target.value)}
                                placeholder={__('workout_pages.form.rest_between_exercises_placeholder')}
                                aria-invalid={!!errors.rest_between_exercises}
                            />
                            <InputError message={errors.rest_between_exercises} />
                        </Field>
                    </div>

                    <Separator />

                    <Field>
                        <FieldLabel htmlFor="is_active">{__('workout_pages.form.is_active')}</FieldLabel>
                        <div className="flex items-center gap-3">
                            <Switch
                                id="is_active"
                                checked={data.is_active}
                                onCheckedChange={(checked) => setData('is_active', Boolean(checked))}
                            />
                            <Badge variant={data.is_active ? 'default' : 'secondary'}>
                                {data.is_active
                                    ? __('workout_pages.shared.active_status')
                                    : __('workout_pages.shared.inactive_status')}
                            </Badge>
                        </div>
                    </Field>
                </CardContent>
            </Card>

            <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle>{__('workout_pages.form.registry_title')}</CardTitle>
                        <CardDescription>{__('workout_pages.form.registry_description')}</CardDescription>
                    </div>

                    <Button type="button" variant="outline" onClick={addSection}>
                        {__('workout_pages.form.add_section')}
                    </Button>
                </CardHeader>
                <CardContent className="space-y-4">
                    {data.sections.length === 0 ? (
                        <Empty className="border">
                            <EmptyHeader>
                                <EmptyTitle>{__('workout_pages.form.empty_sections_title')}</EmptyTitle>
                                <EmptyDescription>
                                    {__('workout_pages.form.empty_sections_description')}
                                </EmptyDescription>
                            </EmptyHeader>
                            <Button type="button" onClick={addSection}>
                                {__('workout_pages.form.add_first_section')}
                            </Button>
                        </Empty>
                    ) : (
                        <Fragment>
                            {data.sections.map((section, sectionIndex) => (
                                <Card key={section.id ?? `section-${sectionIndex}`} className="border bg-muted/10">
                                    <CardHeader className="gap-3">
                                        <div className="flex flex-wrap items-center justify-between gap-2">
                                            <CardTitle>
                                                {__('workout_pages.form.section_label', {
                                                    number: sectionIndex + 1,
                                                })}
                                            </CardTitle>

                                            <div className="flex items-center gap-2">
                                                <Badge variant="outline">
                                                    {__('workout_pages.form.exercise_count_badge', {
                                                        count: section.exercises.length,
                                                    })}
                                                </Badge>
                                                <Button
                                                    type="button"
                                                    variant="outline"
                                                    onClick={() => addExercise(sectionIndex)}
                                                >
                                                    {__('workout_pages.form.add_exercise')}
                                                </Button>
                                                <Button
                                                    type="button"
                                                    variant="destructive"
                                                    onClick={() => removeSection(sectionIndex)}
                                                >
                                                    {__('workout_pages.form.remove_section')}
                                                </Button>
                                            </div>
                                        </div>

                                        <div className="grid gap-4 md:grid-cols-2">
                                            <Field data-invalid={!!errors[`sections.${sectionIndex}.name`]}>
                                                <FieldLabel htmlFor={`section-name-${sectionIndex}`}>
                                                    {__('workout_pages.form.section_name')} <RequiredIndicator />
                                                </FieldLabel>
                                                <Input
                                                    id={`section-name-${sectionIndex}`}
                                                    value={section.name}
                                                    onChange={(event) =>
                                                        updateSection(sectionIndex, { name: event.target.value })
                                                    }
                                                    placeholder={__('workout_pages.form.section_name_placeholder')}
                                                    aria-invalid={!!errors[`sections.${sectionIndex}.name`]}
                                                    required
                                                />
                                                <InputError message={errors[`sections.${sectionIndex}.name`]} />
                                            </Field>
                                        </div>
                                    </CardHeader>

                                    <CardContent className="space-y-3">
                                        {section.exercises.length === 0 ? (
                                            <Empty className="border">
                                                <EmptyHeader>
                                                    <EmptyTitle>
                                                        {__('workout_pages.form.empty_exercises_title')}
                                                    </EmptyTitle>
                                                    <EmptyDescription>
                                                        {__('workout_pages.form.empty_exercises_description')}
                                                    </EmptyDescription>
                                                </EmptyHeader>
                                                <Button type="button" onClick={() => addExercise(sectionIndex)}>
                                                    {__('workout_pages.form.add_first_exercise')}
                                                </Button>
                                            </Empty>
                                        ) : (
                                            section.exercises.map((exercise, exerciseIndex) => {
                                                const supportedMuscleGroups = availableMuscleGroups(
                                                    exercise.exercise_id,
                                                );

                                                return (
                                                    <Card
                                                        key={exercise.id ?? `exercise-${sectionIndex}-${exerciseIndex}`}
                                                        className="border bg-background"
                                                    >
                                                        <CardHeader className="gap-2">
                                                            <div className="flex items-center justify-between gap-2">
                                                                <CardTitle className="text-sm">
                                                                    {__('workout_pages.form.exercise_label', {
                                                                        number: exerciseIndex + 1,
                                                                    })}
                                                                </CardTitle>

                                                                <Button
                                                                    type="button"
                                                                    variant="ghost"
                                                                    onClick={() =>
                                                                        removeExercise(sectionIndex, exerciseIndex)
                                                                    }
                                                                >
                                                                    {__('workout_pages.form.remove_exercise')}
                                                                </Button>
                                                            </div>
                                                        </CardHeader>

                                                        <CardContent className="grid gap-4 md:grid-cols-2">
                                                            <Field
                                                                data-invalid={
                                                                    !!errors[
                                                                        `sections.${sectionIndex}.exercises.${exerciseIndex}.exercise_id`
                                                                    ]
                                                                }
                                                            >
                                                                <FieldLabel>
                                                                    {__('workout_pages.form.exercise')}{' '}
                                                                    <RequiredIndicator />
                                                                </FieldLabel>
                                                                <NativeSelect
                                                                    value={exercise.exercise_id}
                                                                    onChange={(event) =>
                                                                        handleExerciseChange(
                                                                            sectionIndex,
                                                                            exerciseIndex,
                                                                            event.target.value,
                                                                        )
                                                                    }
                                                                    aria-invalid={
                                                                        !!errors[
                                                                            `sections.${sectionIndex}.exercises.${exerciseIndex}.exercise_id`
                                                                        ]
                                                                    }
                                                                    required
                                                                >
                                                                    <NativeSelectOption value="">
                                                                        {__('workout_pages.form.select_exercise')}
                                                                    </NativeSelectOption>
                                                                    {formOptions.exercises.map((option) => (
                                                                        <NativeSelectOption
                                                                            key={option.id}
                                                                            value={option.id}
                                                                        >
                                                                            {option.name}
                                                                        </NativeSelectOption>
                                                                    ))}
                                                                </NativeSelect>
                                                                <InputError
                                                                    message={
                                                                        errors[
                                                                            `sections.${sectionIndex}.exercises.${exerciseIndex}.exercise_id`
                                                                        ]
                                                                    }
                                                                />
                                                            </Field>

                                                            <Field
                                                                data-invalid={
                                                                    !!errors[
                                                                        `sections.${sectionIndex}.exercises.${exerciseIndex}.muscle_group_id`
                                                                    ]
                                                                }
                                                            >
                                                                <FieldLabel>
                                                                    {__('workout_pages.form.muscle_group')}
                                                                </FieldLabel>
                                                                <NativeSelect
                                                                    value={exercise.muscle_group_id}
                                                                    onChange={(event) =>
                                                                        updateExercise(sectionIndex, exerciseIndex, {
                                                                            muscle_group_id:
                                                                                event.target.value === ''
                                                                                    ? ''
                                                                                    : Number(event.target.value),
                                                                        })
                                                                    }
                                                                    disabled={exercise.exercise_id === ''}
                                                                    aria-invalid={
                                                                        !!errors[
                                                                            `sections.${sectionIndex}.exercises.${exerciseIndex}.muscle_group_id`
                                                                        ]
                                                                    }
                                                                >
                                                                    <NativeSelectOption value="">
                                                                        {__('workout_pages.form.select_muscle_group')}
                                                                    </NativeSelectOption>
                                                                    {supportedMuscleGroups.map((option) => (
                                                                        <NativeSelectOption
                                                                            key={option.id}
                                                                            value={option.id}
                                                                        >
                                                                            {option.name}
                                                                        </NativeSelectOption>
                                                                    ))}
                                                                </NativeSelect>
                                                                <FieldDescription>
                                                                    {__('workout_pages.form.muscle_group_hint')}
                                                                </FieldDescription>
                                                                <InputError
                                                                    message={
                                                                        errors[
                                                                            `sections.${sectionIndex}.exercises.${exerciseIndex}.muscle_group_id`
                                                                        ]
                                                                    }
                                                                />
                                                            </Field>

                                                            <Field
                                                                data-invalid={
                                                                    !!errors[
                                                                        `sections.${sectionIndex}.exercises.${exerciseIndex}.sets`
                                                                    ]
                                                                }
                                                            >
                                                                <FieldLabel>
                                                                    {__('workout_pages.form.sets')}{' '}
                                                                    <RequiredIndicator />
                                                                </FieldLabel>
                                                                <Input
                                                                    type="number"
                                                                    min={1}
                                                                    value={exercise.sets}
                                                                    onChange={(event) =>
                                                                        updateExercise(sectionIndex, exerciseIndex, {
                                                                            sets: Number(event.target.value || 1),
                                                                        })
                                                                    }
                                                                    required
                                                                />
                                                                <InputError
                                                                    message={
                                                                        errors[
                                                                            `sections.${sectionIndex}.exercises.${exerciseIndex}.sets`
                                                                        ]
                                                                    }
                                                                />
                                                            </Field>

                                                            <Field
                                                                data-invalid={
                                                                    !!errors[
                                                                        `sections.${sectionIndex}.exercises.${exerciseIndex}.reps`
                                                                    ]
                                                                }
                                                            >
                                                                <FieldLabel>
                                                                    {__('workout_pages.form.reps')}{' '}
                                                                    <RequiredIndicator />
                                                                </FieldLabel>
                                                                <Input
                                                                    value={exercise.reps}
                                                                    onChange={(event) =>
                                                                        updateExercise(sectionIndex, exerciseIndex, {
                                                                            reps: event.target.value,
                                                                        })
                                                                    }
                                                                    placeholder={__(
                                                                        'workout_pages.form.reps_placeholder',
                                                                    )}
                                                                    required
                                                                />
                                                                <InputError
                                                                    message={
                                                                        errors[
                                                                            `sections.${sectionIndex}.exercises.${exerciseIndex}.reps`
                                                                        ]
                                                                    }
                                                                />
                                                            </Field>

                                                            <Field>
                                                                <FieldLabel>{__('workout_pages.form.code')}</FieldLabel>
                                                                <Input
                                                                    value={exercise.code}
                                                                    onChange={(event) =>
                                                                        updateExercise(sectionIndex, exerciseIndex, {
                                                                            code: event.target.value,
                                                                        })
                                                                    }
                                                                    placeholder={__(
                                                                        'workout_pages.form.code_placeholder',
                                                                    )}
                                                                />
                                                            </Field>

                                                            <Field
                                                                data-invalid={
                                                                    !!errors[
                                                                        `sections.${sectionIndex}.exercises.${exerciseIndex}.load`
                                                                    ]
                                                                }
                                                            >
                                                                <FieldLabel>{__('workout_pages.form.load')}</FieldLabel>
                                                                <div className="grid grid-cols-3 gap-2">
                                                                    <Input
                                                                        className="col-span-2"
                                                                        type="number"
                                                                        min={0}
                                                                        step={0.01}
                                                                        value={exercise.load}
                                                                        onChange={(event) =>
                                                                            updateExercise(
                                                                                sectionIndex,
                                                                                exerciseIndex,
                                                                                {
                                                                                    load: event.target.value,
                                                                                },
                                                                            )
                                                                        }
                                                                        placeholder={__(
                                                                            'workout_pages.form.load_placeholder',
                                                                        )}
                                                                    />

                                                                    <NativeSelect
                                                                        value={exercise.load_unit}
                                                                        onChange={(event) =>
                                                                            updateExercise(
                                                                                sectionIndex,
                                                                                exerciseIndex,
                                                                                {
                                                                                    load_unit: event.target.value,
                                                                                },
                                                                            )
                                                                        }
                                                                    >
                                                                        <NativeSelectOption value="kg">
                                                                            kg
                                                                        </NativeSelectOption>
                                                                        <NativeSelectOption value="lbs">
                                                                            lbs
                                                                        </NativeSelectOption>
                                                                        <NativeSelectOption value="bodyweight">
                                                                            bodyweight
                                                                        </NativeSelectOption>
                                                                    </NativeSelect>
                                                                </div>
                                                                <InputError
                                                                    message={
                                                                        errors[
                                                                            `sections.${sectionIndex}.exercises.${exerciseIndex}.load`
                                                                        ]
                                                                    }
                                                                />
                                                            </Field>

                                                            <Field>
                                                                <FieldLabel>
                                                                    {__('workout_pages.form.rest_seconds')}
                                                                </FieldLabel>
                                                                <Input
                                                                    type="number"
                                                                    min={0}
                                                                    value={exercise.rest_seconds}
                                                                    onChange={(event) =>
                                                                        updateExercise(sectionIndex, exerciseIndex, {
                                                                            rest_seconds: event.target.value,
                                                                        })
                                                                    }
                                                                    placeholder={__(
                                                                        'workout_pages.form.rest_seconds_placeholder',
                                                                    )}
                                                                />
                                                            </Field>

                                                            <Field className="md:col-span-2">
                                                                <FieldLabel>
                                                                    {__('workout_pages.form.notes')}
                                                                </FieldLabel>
                                                                <Textarea
                                                                    value={exercise.notes}
                                                                    onChange={(event) =>
                                                                        updateExercise(sectionIndex, exerciseIndex, {
                                                                            notes: event.target.value,
                                                                        })
                                                                    }
                                                                    placeholder={__(
                                                                        'workout_pages.form.notes_placeholder',
                                                                    )}
                                                                />
                                                            </Field>
                                                        </CardContent>
                                                    </Card>
                                                );
                                            })
                                        )}
                                    </CardContent>
                                </Card>
                            ))}
                        </Fragment>
                    )}

                    <InputError message={errors.sections} />
                </CardContent>
            </Card>

            <div className="flex flex-wrap items-center gap-2">
                <Button type="submit" disabled={processing}>
                    <Activity mode={processing ? 'visible' : 'hidden'}>
                        <Spinner aria-hidden />
                    </Activity>
                    {isEditMode ? __('workout_pages.form.update_button') : __('workout_pages.form.create_button')}
                </Button>

                <Button variant="outline" render={<Link href={index()}>{__('workout_pages.shared.cancel')}</Link>} />
                {!isEditMode && (
                    <Button variant="ghost" render={<Link href={create()}>{__('workout_pages.shared.reset')}</Link>} />
                )}
            </div>
        </form>
    );
}
