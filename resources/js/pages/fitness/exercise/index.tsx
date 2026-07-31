import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldDescription, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import { destroy, index, store, update } from '@/routes/exercises';
import type { CatalogExercise, CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps, useForm } from '@inertiajs/react';
import { Fragment, useState } from 'react';

type Props = {
    exercises: CatalogExercise[];
    muscleGroups: CatalogMuscleGroup[];
};

type FormData = {
    name: string;
    description: string;
    video_url: string;
    muscle_group_ids: number[];
};

const emptyForm: FormData = {
    name: '',
    description: '',
    video_url: '',
    muscle_group_ids: [],
};

export default function Index({ exercises, muscleGroups }: Readonly<Props>) {
    const { __ } = lang();
    const [editingExerciseId, setEditingExerciseId] = useState<number | null>(null);

    const { data, setData, post, put, processing, errors, reset } = useForm<FormData>(emptyForm);

    setLayoutProps({
        title: __('exercise_pages.index.title'),
        description: __('exercise_pages.index.description'),
        breadcrumbs: [{ title: __('exercise_pages.index.breadcrumbs.current'), href: index() }],
    });

    const onToggleMuscleGroup = (muscleGroupId: number) => {
        setData(
            'muscle_group_ids',
            data.muscle_group_ids.includes(muscleGroupId)
                ? data.muscle_group_ids.filter((item) => item !== muscleGroupId)
                : [...data.muscle_group_ids, muscleGroupId],
        );
    };

    const onSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (editingExerciseId !== null) {
            put(update(editingExerciseId).url, {
                preserveScroll: true,
                onSuccess: () => {
                    setEditingExerciseId(null);
                    reset();
                },
            });

            return;
        }

        post(store().url, {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    };

    const onEdit = (exercise: CatalogExercise) => {
        setEditingExerciseId(exercise.id);
        setData({
            name: exercise.name,
            description: exercise.description ?? '',
            video_url: exercise.video_url ?? '',
            muscle_group_ids: exercise.muscle_groups.map((muscleGroup) => muscleGroup.id),
        });
    };

    return (
        <Fragment>
            <Head title={__('exercise_pages.index.head_title')} />
            <h1 className="sr-only">{__('exercise_pages.index.head_title')}</h1>

            <div className="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>{__('exercise_pages.form.title')}</CardTitle>
                        <CardDescription>{__('exercise_pages.form.description')}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={onSubmit} className="space-y-4">
                            <Field data-invalid={!!errors.name}>
                                <FieldLabel htmlFor="name">{__('exercise_pages.form.name')}</FieldLabel>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(event) => setData('name', event.target.value)}
                                    placeholder={__('exercise_pages.form.name_placeholder')}
                                    aria-invalid={!!errors.name}
                                    required
                                />
                                {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                            </Field>

                            <Field data-invalid={!!errors.description}>
                                <FieldLabel htmlFor="description">
                                    {__('exercise_pages.form.description_field')}
                                </FieldLabel>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(event) => setData('description', event.target.value)}
                                    placeholder={__('exercise_pages.form.description_placeholder')}
                                    aria-invalid={!!errors.description}
                                />
                                {errors.description && <p className="text-sm text-destructive">{errors.description}</p>}
                            </Field>

                            <Field data-invalid={!!errors.video_url}>
                                <FieldLabel htmlFor="video_url">{__('exercise_pages.form.video_url')}</FieldLabel>
                                <Input
                                    id="video_url"
                                    type="url"
                                    value={data.video_url}
                                    onChange={(event) => setData('video_url', event.target.value)}
                                    placeholder={__('exercise_pages.form.video_url_placeholder')}
                                    aria-invalid={!!errors.video_url}
                                />
                                {errors.video_url && <p className="text-sm text-destructive">{errors.video_url}</p>}
                            </Field>

                            <Field>
                                <FieldLabel>{__('exercise_pages.form.muscle_groups')}</FieldLabel>
                                <FieldDescription>{__('exercise_pages.form.muscle_groups_hint')}</FieldDescription>
                                <div className="grid gap-2 sm:grid-cols-2">
                                    {muscleGroups.map((muscleGroup) => (
                                        <label
                                            key={muscleGroup.id}
                                            className="flex items-center gap-2 rounded-xl border px-3 py-2 text-sm"
                                        >
                                            <input
                                                type="checkbox"
                                                checked={data.muscle_group_ids.includes(muscleGroup.id)}
                                                onChange={() => onToggleMuscleGroup(muscleGroup.id)}
                                            />
                                            <span>{muscleGroup.name}</span>
                                        </label>
                                    ))}
                                </div>
                            </Field>

                            <div className="flex flex-wrap gap-2">
                                <Button type="submit" disabled={processing}>
                                    {editingExerciseId === null
                                        ? __('exercise_pages.form.create_action')
                                        : __('exercise_pages.form.update_action')}
                                </Button>
                                {editingExerciseId !== null && (
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => {
                                            setEditingExerciseId(null);
                                            reset();
                                        }}
                                    >
                                        {__('exercise_pages.form.cancel_action')}
                                    </Button>
                                )}
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{__('exercise_pages.index.table.title')}</CardTitle>
                        <CardDescription>
                            {__('exercise_pages.index.table.description', { count: exercises.length })}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>{__('exercise_pages.index.table.columns.name')}</TableHead>
                                    <TableHead>{__('exercise_pages.index.table.columns.muscle_groups')}</TableHead>
                                    <TableHead>{__('exercise_pages.index.table.columns.video')}</TableHead>
                                    <TableHead className="text-right">
                                        {__('exercise_pages.index.table.columns.actions')}
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {exercises.map((exercise) => (
                                    <TableRow key={exercise.id}>
                                        <TableCell>
                                            <div className="font-medium">{exercise.name}</div>
                                            {exercise.description && (
                                                <p className="text-xs text-muted-foreground">{exercise.description}</p>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            <div className="flex flex-wrap gap-1">
                                                {exercise.muscle_groups.map((muscleGroup) => (
                                                    <Badge key={muscleGroup.id} variant="outline">
                                                        {muscleGroup.name}
                                                    </Badge>
                                                ))}
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            {exercise.video_url ? (
                                                <a
                                                    href={exercise.video_url}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="text-sm underline"
                                                >
                                                    {__('exercise_pages.index.table.open_video')}
                                                </a>
                                            ) : (
                                                <span className="text-sm text-muted-foreground">-</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            <div className="flex justify-end gap-2">
                                                <Button size="sm" variant="outline" onClick={() => onEdit(exercise)}>
                                                    {__('exercise_pages.index.table.edit')}
                                                </Button>
                                                <Button
                                                    size="sm"
                                                    variant="destructive"
                                                    render={
                                                        <Link href={destroy(exercise.id)} method="delete" as="button">
                                                            {__('exercise_pages.index.table.delete')}
                                                        </Link>
                                                    }
                                                />
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </Fragment>
    );
}
