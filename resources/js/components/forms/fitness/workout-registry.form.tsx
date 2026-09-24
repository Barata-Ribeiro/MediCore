import InputError from '@/components/helpers/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import {
    Combobox,
    ComboboxContent,
    ComboboxEmpty,
    ComboboxInput,
    ComboboxItem,
    ComboboxList,
} from '@/components/ui/combobox';
import { Empty, EmptyDescription, EmptyHeader, EmptyTitle } from '@/components/ui/empty';
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { create as createExercise, edit as editExercise } from '@/routes/exercises';
import { create as createMuscleGroup } from '@/routes/muscle-groups';
import { index, show, store, update } from '@/routes/workouts';
import type { WorkoutFormOptions, WorkoutOptionExercise, WorkoutResource } from '@/types/application/fitness/workout';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link, useForm } from '@inertiajs/react';
import { ModalLink } from '@inertiaui/modal-react';
import { ArrowDownIcon, ArrowUpIcon, ChevronDownIcon, PencilIcon, PlusIcon, SaveIcon, Trash2Icon } from 'lucide-react';
import { type SubmitEvent, useState } from 'react';

type ExerciseInput = {
    key: string;
    id?: number;
    exercise_id: number | '';
    muscle_group_id: number | '';
    code: string;
    order: number;
    sets: number | '';
    reps: string;
    load: string;
    load_unit: string;
    rest_seconds: string;
    notes: string;
};

type SectionInput = { key: string; id?: number; name: string; order: number; exercises: ExerciseInput[] };

type FormData = {
    filled_at: string;
    next_change_at: string;
    goal: string;
    method: string;
    rest_between_sets: string;
    rest_between_exercises: string;
    is_active: boolean;
    sections: SectionInput[];
};

type Props = { workout?: WorkoutResource; formOptions: WorkoutFormOptions };

function newExercise(): ExerciseInput {
    return {
        key: crypto.randomUUID(),
        exercise_id: '',
        muscle_group_id: '',
        code: '',
        order: 1,
        sets: 3,
        reps: '8-12',
        load: '',
        load_unit: 'kg',
        rest_seconds: '',
        notes: '',
    };
}

function newSection(name: string): SectionInput {
    return { key: crypto.randomUUID(), name, order: 1, exercises: [newExercise()] };
}

export default function WorkoutRegistryForm({ workout, formOptions }: Readonly<Props>) {
    const { __ } = lang();
    const [catalog, setCatalog] = useState(formOptions.exercises);
    const [initial] = useState<FormData>(() => ({
        filled_at: workout?.filled_at?.slice(0, 10) ?? '',
        next_change_at: workout?.next_change_at?.slice(0, 10) ?? '',
        goal: workout?.goal ?? '',
        method: workout?.method ?? '',
        rest_between_sets: String(workout?.rest_between_sets ?? ''),
        rest_between_exercises: String(workout?.rest_between_exercises ?? ''),
        is_active: workout?.is_active ?? true,
        sections: workout?.sections.map((section) => ({
            ...section,
            key: `section-${section.id}`,
            exercises: section.exercises.map((exercise) => ({
                ...exercise,
                key: `exercise-${exercise.id}`,
                muscle_group_id: exercise.muscle_group_id ?? '',
                code: exercise.code ?? '',
                load: String(exercise.load ?? ''),
                rest_seconds: String(exercise.rest_seconds ?? ''),
                notes: exercise.notes ?? '',
            })),
        })) ?? [newSection(__('workout_pages.form.default_section', { number: 1 }))],
    }));
    const { data, setData, post, put, processing, errors, transform, clearErrors } = useForm<FormData>(initial);

    const error = (path: string) => (errors as Record<string, string>)[path];

    const replaceSections = (sections: SectionInput[]) => {
        clearErrors();
        setData(
            'sections',
            sections.map((section, i) => ({
                ...section,
                order: i + 1,
                exercises: section.exercises.map((exercise, j) => ({ ...exercise, order: j + 1 })),
            })),
        );
    };

    const updateSection = (key: string, patch: Partial<SectionInput>) =>
        replaceSections(data.sections.map((section) => (section.key === key ? { ...section, ...patch } : section)));

    const updateExercise = (sectionKey: string, exerciseKey: string, patch: Partial<ExerciseInput>) =>
        replaceSections(
            data.sections.map((section) =>
                section.key === sectionKey
                    ? {
                          ...section,
                          exercises: section.exercises.map((exercise) =>
                              exercise.key === exerciseKey ? { ...exercise, ...patch } : exercise,
                          ),
                      }
                    : section,
            ),
        );

    const move = <T,>(items: T[], position: number, direction: number): T[] => {
        const result = [...items];
        const current = result[position];
        const target = result[position + direction];

        if (current === undefined || target === undefined) {
            return result;
        }

        result[position] = target;
        result[position + direction] = current;
        return result;
    };

    const saveCatalogExercise = (saved: WorkoutOptionExercise, sectionKey: string, exerciseKey: string) => {
        setCatalog((current) =>
            [...current.filter((item) => item.id !== saved.id), saved].sort((a, b) => a.name.localeCompare(b.name)),
        );

        replaceSections(
            data.sections.map((section) => ({
                ...section,
                exercises: section.exercises.map((exercise) => {
                    const selectedRow = section.key === sectionKey && exercise.key === exerciseKey;

                    if (!selectedRow && exercise.exercise_id !== saved.id) {
                        return exercise;
                    }

                    return {
                        ...exercise,
                        exercise_id: saved.id,
                        muscle_group_id: saved.muscle_groups.some((group) => group.id === exercise.muscle_group_id)
                            ? exercise.muscle_group_id
                            : (saved.muscle_groups[0]?.id ?? ''),
                    };
                }),
            })),
        );
    };

    const submit = (event: SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();

        transform((values) => ({
            ...values,
            filled_at: values.filled_at || null,
            next_change_at: values.next_change_at || null,
            goal: values.goal || null,
            method: values.method || null,
            rest_between_sets: values.rest_between_sets === '' ? null : Number(values.rest_between_sets),
            rest_between_exercises: values.rest_between_exercises === '' ? null : Number(values.rest_between_exercises),
            sections: values.sections.map((section, i) => ({
                id: section.id,
                name: section.name,
                order: i + 1,
                exercises: section.exercises.map((exercise, j) => ({
                    id: exercise.id,
                    exercise_id: exercise.exercise_id,
                    muscle_group_id: exercise.muscle_group_id || null,
                    code: exercise.code || null,
                    order: j + 1,
                    sets: exercise.sets,
                    reps: exercise.reps,
                    load: exercise.load === '' ? null : Number(exercise.load),
                    load_unit: exercise.load_unit,
                    rest_seconds: exercise.rest_seconds === '' ? null : Number(exercise.rest_seconds),
                    notes: exercise.notes || null,
                })),
            })),
        }));

        if (workout) {
            put(update(workout.id).url, { preserveScroll: true });
        } else {
            post(store().url, { preserveScroll: true });
        }
    };

    const addSection = () =>
        replaceSections([
            ...data.sections,
            newSection(__('workout_pages.form.default_section', { number: data.sections.length + 1 })),
        ]);

    return (
        <form onSubmit={submit} className="flex flex-col gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>{__('workout_pages.form.identity_title')}</CardTitle>
                    <CardDescription>{__('workout_pages.form.identity_description')}</CardDescription>
                </CardHeader>

                <CardContent>
                    <FieldGroup className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        {(['goal', 'method', 'filled_at', 'next_change_at'] as const).map((name) => (
                            <Field key={name} data-invalid={!!errors[name]}>
                                <FieldLabel htmlFor={name}>{__('workout_pages.form.' + name)}</FieldLabel>
                                <Input
                                    id={name}
                                    type={name.endsWith('_at') ? 'date' : 'text'}
                                    value={data[name]}
                                    onChange={(event) => setData(name, event.target.value)}
                                    placeholder={
                                        name.endsWith('_at')
                                            ? undefined
                                            : __('workout_pages.form.' + name + '_placeholder')
                                    }
                                    aria-invalid={!!errors[name]}
                                />
                                <InputError message={errors[name]} />
                            </Field>
                        ))}

                        {(['rest_between_sets', 'rest_between_exercises'] as const).map((name) => (
                            <Field key={name} data-invalid={!!errors[name]}>
                                <FieldLabel htmlFor={name}>{__('workout_pages.form.' + name)}</FieldLabel>
                                <Input
                                    id={name}
                                    type="number"
                                    min={0}
                                    value={data[name]}
                                    onChange={(event) => setData(name, event.target.value)}
                                    placeholder={__('workout_pages.form.' + name + '_placeholder')}
                                    aria-invalid={!!errors[name]}
                                />
                                <InputError message={errors[name]} />
                            </Field>
                        ))}

                        <Field orientation="horizontal" className="sm:col-span-2">
                            <Switch
                                id="is_active"
                                checked={data.is_active}
                                onCheckedChange={(checked) => setData('is_active', checked)}
                            />
                            <FieldLabel htmlFor="is_active">{__('workout_pages.form.is_active')}</FieldLabel>
                        </Field>
                    </FieldGroup>
                </CardContent>
            </Card>

            <div className="flex flex-wrap items-center justify-between gap-3">
                <div className="flex flex-col gap-1">
                    <h2 className="font-heading text-lg font-medium">{__('workout_pages.form.registry_title')}</h2>
                    <p className="text-sm text-muted-foreground">{__('workout_pages.form.registry_description')}</p>
                </div>

                <Button type="button" variant="outline" onClick={addSection}>
                    <PlusIcon aria-hidden data-icon="inline-start" />
                    {__('workout_pages.form.add_section')}
                </Button>
            </div>

            <InputError message={errors.sections} />

            {data.sections.length === 0 && (
                <Empty className="border">
                    <EmptyHeader>
                        <EmptyTitle>{__('workout_pages.form.empty_sections_title')}</EmptyTitle>
                        <EmptyDescription>{__('workout_pages.form.empty_sections_description')}</EmptyDescription>
                    </EmptyHeader>
                    <Button type="button" onClick={addSection}>
                        {__('workout_pages.form.add_first_section')}
                    </Button>
                </Empty>
            )}

            {data.sections.map((section, sectionIndex) => (
                <Card key={section.key}>
                    <CardHeader>
                        <div className="flex flex-wrap items-center justify-between gap-3">
                            <CardTitle>
                                {__('workout_pages.form.section_label', { number: sectionIndex + 1 })}
                            </CardTitle>

                            <div className="flex items-center gap-1">
                                <Badge variant="secondary">
                                    {__('workout_pages.form.exercise_count_badge', { count: section.exercises.length })}
                                </Badge>

                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    disabled={sectionIndex === 0}
                                    onClick={() => replaceSections(move(data.sections, sectionIndex, -1))}
                                    aria-label={__('workout_pages.form.move_up')}
                                    title={__('workout_pages.form.move_up')}
                                >
                                    <ArrowUpIcon aria-hidden />
                                </Button>

                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    disabled={sectionIndex === data.sections.length - 1}
                                    onClick={() => replaceSections(move(data.sections, sectionIndex, 1))}
                                    aria-label={__('workout_pages.form.move_down')}
                                    title={__('workout_pages.form.move_down')}
                                >
                                    <ArrowDownIcon aria-hidden />
                                </Button>
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    onClick={() =>
                                        replaceSections(data.sections.filter((item) => item.key !== section.key))
                                    }
                                    aria-label={__('workout_pages.form.remove_section')}
                                    title={__('workout_pages.form.remove_section')}
                                >
                                    <Trash2Icon aria-hidden />
                                </Button>
                            </div>
                        </div>
                        <CardDescription>{__('workout_pages.form.section_hint')}</CardDescription>
                    </CardHeader>

                    <CardContent className="flex flex-col gap-5">
                        <FieldGroup>
                            <Field data-invalid={!!error(`sections.${sectionIndex}.name`)}>
                                <FieldLabel htmlFor={`section-${sectionIndex}`}>
                                    {__('workout_pages.form.section_name')}
                                </FieldLabel>
                                <Input
                                    id={`section-${sectionIndex}`}
                                    value={section.name}
                                    required
                                    maxLength={255}
                                    onChange={(event) => updateSection(section.key, { name: event.target.value })}
                                    placeholder={__('workout_pages.form.section_name_placeholder')}
                                    aria-invalid={!!error(`sections.${sectionIndex}.name`)}
                                />
                                <InputError message={error(`sections.${sectionIndex}.name`)} />
                            </Field>
                        </FieldGroup>

                        <InputError message={error(`sections.${sectionIndex}.exercises`)} />

                        {section.exercises.length === 0 && (
                            <Empty>
                                <EmptyHeader>
                                    <EmptyTitle>{__('workout_pages.form.empty_exercises_title')}</EmptyTitle>
                                    <EmptyDescription>
                                        {__('workout_pages.form.empty_exercises_description')}
                                    </EmptyDescription>
                                </EmptyHeader>
                            </Empty>
                        )}

                        {section.exercises.map((exercise, exerciseIndex) => {
                            const path = `sections.${sectionIndex}.exercises.${exerciseIndex}`;
                            const selected = catalog.find((item) => item.id === exercise.exercise_id) ?? null;
                            const change = (patch: Partial<ExerciseInput>) =>
                                updateExercise(section.key, exercise.key, patch);
                            return (
                                <div key={exercise.key} className="flex flex-col gap-4">
                                    <Separator />
                                    <div className="flex items-center justify-between gap-3">
                                        <Badge variant="outline">
                                            {__('workout_pages.form.exercise_label', { number: exerciseIndex + 1 })}
                                        </Badge>

                                        <div className="flex gap-1">
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                disabled={exerciseIndex === 0}
                                                onClick={() =>
                                                    updateSection(section.key, {
                                                        exercises: move(section.exercises, exerciseIndex, -1),
                                                    })
                                                }
                                                aria-label={__('workout_pages.form.move_up')}
                                            >
                                                <ArrowUpIcon aria-hidden />
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                disabled={exerciseIndex === section.exercises.length - 1}
                                                onClick={() =>
                                                    updateSection(section.key, {
                                                        exercises: move(section.exercises, exerciseIndex, 1),
                                                    })
                                                }
                                                aria-label={__('workout_pages.form.move_down')}
                                                title={__('workout_pages.form.move_down')}
                                            >
                                                <ArrowDownIcon aria-hidden />
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                onClick={() =>
                                                    updateSection(section.key, {
                                                        exercises: section.exercises.filter(
                                                            (item) => item.key !== exercise.key,
                                                        ),
                                                    })
                                                }
                                                aria-label={__('workout_pages.form.remove_exercise')}
                                                title={__('workout_pages.form.remove_exercise')}
                                            >
                                                <Trash2Icon aria-hidden />
                                            </Button>
                                        </div>
                                    </div>

                                    <FieldGroup className="grid gap-4 sm:grid-cols-2 xl:grid-cols-12">
                                        <Field
                                            className="sm:col-span-2 xl:col-span-4"
                                            data-invalid={!!error(`${path}.exercise_id`)}
                                        >
                                            <FieldLabel htmlFor={`${path}.exercise_id`}>
                                                {__('workout_pages.form.exercise')}
                                            </FieldLabel>
                                            <Combobox
                                                items={catalog}
                                                value={selected}
                                                itemToStringLabel={(item) => item.name}
                                                isItemEqualToValue={(a, b) => a.id === b.id}
                                                onValueChange={(item) =>
                                                    change({
                                                        exercise_id: item?.id ?? '',
                                                        muscle_group_id: item?.muscle_groups[0]?.id ?? '',
                                                    })
                                                }
                                            >
                                                <ComboboxInput
                                                    id={`${path}.exercise_id`}
                                                    placeholder={__('workout_pages.form.select_exercise')}
                                                    aria-invalid={!!error(`${path}.exercise_id`)}
                                                />
                                                <ComboboxContent>
                                                    <ComboboxEmpty>
                                                        {__('workout_pages.form.no_exercises_found')}
                                                    </ComboboxEmpty>
                                                    <ComboboxList>
                                                        {(item: WorkoutOptionExercise) => (
                                                            <ComboboxItem key={item.id} value={item}>
                                                                {item.name}
                                                            </ComboboxItem>
                                                        )}
                                                    </ComboboxList>
                                                </ComboboxContent>
                                            </Combobox>
                                            <InputError message={error(`${path}.exercise_id`)} />

                                            <div className="flex flex-wrap gap-2">
                                                <Button
                                                    type="button"
                                                    nativeButton={false}
                                                    variant="outline"
                                                    size="sm"
                                                    render={
                                                        <ModalLink
                                                            href={createExercise().url}
                                                            onSaved={(saved: WorkoutOptionExercise) =>
                                                                saveCatalogExercise(saved, section.key, exercise.key)
                                                            }
                                                            as="button"
                                                        >
                                                            <PlusIcon aria-hidden data-icon="inline-start" />
                                                            {__('workout_pages.form.new_exercise')}
                                                        </ModalLink>
                                                    }
                                                />

                                                {selected && (
                                                    <Button
                                                        type="button"
                                                        nativeButton={false}
                                                        variant="ghost"
                                                        size="sm"
                                                        render={
                                                            <ModalLink
                                                                href={editExercise(selected.id).url}
                                                                onSaved={(saved: WorkoutOptionExercise) =>
                                                                    saveCatalogExercise(
                                                                        saved,
                                                                        section.key,
                                                                        exercise.key,
                                                                    )
                                                                }
                                                                as="button"
                                                            >
                                                                <PencilIcon aria-hidden data-icon="inline-start" />
                                                                {__('workout_pages.form.edit_catalog_exercise')}
                                                            </ModalLink>
                                                        }
                                                    />
                                                )}
                                            </div>
                                        </Field>

                                        {(['sets', 'reps', 'load', 'rest_seconds'] as const).map((name) => (
                                            <Field
                                                key={name}
                                                className="xl:col-span-2"
                                                data-invalid={!!error(`${path}.${name}`)}
                                            >
                                                <FieldLabel htmlFor={`${path}.${name}`}>
                                                    {__('workout_pages.form.' + name)}
                                                    {name === 'load' && ` (${exercise.load_unit})`}
                                                </FieldLabel>
                                                <Input
                                                    id={`${path}.${name}`}
                                                    type={name === 'reps' ? 'text' : 'number'}
                                                    min={name === 'sets' ? 1 : 0}
                                                    step={name === 'load' ? 'any' : undefined}
                                                    required={name === 'sets' || name === 'reps'}
                                                    value={exercise[name]}
                                                    onChange={(event) =>
                                                        change({
                                                            [name]:
                                                                name === 'sets' && event.target.value !== ''
                                                                    ? Number(event.target.value)
                                                                    : event.target.value,
                                                        })
                                                    }
                                                    placeholder={
                                                        name === 'rest_seconds'
                                                            ? data.rest_between_sets || '—'
                                                            : undefined
                                                    }
                                                    aria-invalid={!!error(`${path}.${name}`)}
                                                />
                                                <InputError message={error(`${path}.${name}`)} />
                                            </Field>
                                        ))}
                                    </FieldGroup>
                                    <Collapsible
                                        key={String(
                                            !!error(`${path}.code`) ||
                                                !!error(`${path}.load_unit`) ||
                                                !!error(`${path}.muscle_group_id`),
                                        )}
                                        defaultOpen={
                                            !!exercise.notes ||
                                            !!exercise.code ||
                                            !!error(`${path}.code`) ||
                                            !!error(`${path}.load_unit`) ||
                                            !!error(`${path}.muscle_group_id`)
                                        }
                                    >
                                        <CollapsibleTrigger render={<Button type="button" variant="ghost" size="sm" />}>
                                            <ChevronDownIcon aria-hidden data-icon="inline-start" />
                                            {__('workout_pages.form.more_details')}
                                        </CollapsibleTrigger>
                                        <CollapsibleContent keepMounted>
                                            <FieldGroup className="grid gap-4 pt-4 sm:grid-cols-3">
                                                <Field data-invalid={!!error(`${path}.muscle_group_id`)}>
                                                    <FieldLabel htmlFor={`${path}.muscle_group_id`}>
                                                        {__('workout_pages.form.muscle_group')}
                                                    </FieldLabel>
                                                    <NativeSelect
                                                        id={`${path}.muscle_group_id`}
                                                        value={exercise.muscle_group_id}
                                                        onChange={(event) =>
                                                            change({
                                                                muscle_group_id: event.target.value
                                                                    ? Number(event.target.value)
                                                                    : '',
                                                            })
                                                        }
                                                        aria-invalid={!!error(`${path}.muscle_group_id`)}
                                                    >
                                                        <NativeSelectOption value="">
                                                            {__('workout_pages.form.select_muscle_group')}
                                                        </NativeSelectOption>
                                                        {selected?.muscle_groups.map((group) => (
                                                            <NativeSelectOption key={group.id} value={group.id}>
                                                                {group.name}
                                                            </NativeSelectOption>
                                                        ))}
                                                    </NativeSelect>
                                                    <FieldDescription>
                                                        {__('workout_pages.form.muscle_group_hint')}
                                                    </FieldDescription>
                                                    <InputError message={error(`${path}.muscle_group_id`)} />
                                                </Field>
                                                {(['code', 'load_unit'] as const).map((name) => (
                                                    <Field key={name} data-invalid={!!error(`${path}.${name}`)}>
                                                        <FieldLabel htmlFor={`${path}.${name}`}>
                                                            {__('workout_pages.form.' + name)}
                                                        </FieldLabel>
                                                        <Input
                                                            id={`${path}.${name}`}
                                                            value={exercise[name]}
                                                            maxLength={name === 'code' ? 10 : 25}
                                                            onChange={(event) => change({ [name]: event.target.value })}
                                                            aria-invalid={!!error(`${path}.${name}`)}
                                                        />
                                                        <InputError message={error(`${path}.${name}`)} />
                                                    </Field>
                                                ))}
                                                <Field
                                                    className="sm:col-span-3"
                                                    data-invalid={!!error(`${path}.notes`)}
                                                >
                                                    <FieldLabel htmlFor={`${path}.notes`}>
                                                        {__('workout_pages.form.notes')}
                                                    </FieldLabel>
                                                    <Textarea
                                                        id={`${path}.notes`}
                                                        value={exercise.notes}
                                                        onChange={(event) => change({ notes: event.target.value })}
                                                        aria-invalid={!!error(`${path}.notes`)}
                                                    />
                                                    <InputError message={error(`${path}.notes`)} />
                                                </Field>
                                            </FieldGroup>
                                        </CollapsibleContent>
                                    </Collapsible>
                                </div>
                            );
                        })}
                    </CardContent>
                    <CardFooter>
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() =>
                                updateSection(section.key, { exercises: [...section.exercises, newExercise()] })
                            }
                        >
                            <PlusIcon aria-hidden data-icon="inline-start" />
                            {__('workout_pages.form.add_exercise')}
                        </Button>
                    </CardFooter>
                </Card>
            ))}
            <p className="text-sm text-muted-foreground">
                {__('workout_pages.form.catalog_hint')}
                <Button
                    type="button"
                    nativeButton={false}
                    variant="link"
                    render={
                        <ModalLink href={createMuscleGroup().url} method={createMuscleGroup().method}>
                            {__('workout_pages.form.new_muscle_group')}
                        </ModalLink>
                    }
                />
            </p>
            <div className="sticky bottom-3 flex flex-wrap items-center justify-between gap-3 rounded-xl border bg-background p-3 shadow-sm">
                <Button
                    nativeButton={false}
                    variant="outline"
                    render={<Link href={workout ? show(workout.id) : index()} />}
                >
                    {__('workout_pages.shared.cancel')}
                </Button>
                <Button type="submit" disabled={processing}>
                    {processing ? (
                        <Spinner data-icon="inline-start" />
                    ) : (
                        <SaveIcon aria-hidden data-icon="inline-start" />
                    )}
                    {__('workout_pages.form.' + (workout ? 'update_button' : 'create_button'))}
                </Button>
            </div>
        </form>
    );
}
