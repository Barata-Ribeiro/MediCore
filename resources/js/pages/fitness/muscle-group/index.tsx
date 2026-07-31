import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { destroy, index, store, update } from '@/routes/muscle-groups';
import type { CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps, useForm } from '@inertiajs/react';
import { Fragment, useState } from 'react';

type Props = {
    muscleGroups: CatalogMuscleGroup[];
};

type FormData = {
    name: string;
};

export default function Index({ muscleGroups }: Readonly<Props>) {
    const { __ } = lang();
    const [editingMuscleGroupId, setEditingMuscleGroupId] = useState<number | null>(null);

    const { data, setData, post, put, processing, errors, reset } = useForm<FormData>({
        name: '',
    });

    setLayoutProps({
        title: __('muscle_group_pages.index.title'),
        description: __('muscle_group_pages.index.description'),
        breadcrumbs: [{ title: __('muscle_group_pages.index.breadcrumbs.current'), href: index() }],
    });

    const onSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (editingMuscleGroupId !== null) {
            put(update(editingMuscleGroupId).url, {
                preserveScroll: true,
                onSuccess: () => {
                    setEditingMuscleGroupId(null);
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

    return (
        <Fragment>
            <Head title={__('muscle_group_pages.index.head_title')} />

            <div className="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>{__('muscle_group_pages.form.title')}</CardTitle>
                        <CardDescription>{__('muscle_group_pages.form.description')}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={onSubmit} className="space-y-4">
                            <Field data-invalid={!!errors.name}>
                                <FieldLabel htmlFor="name">{__('muscle_group_pages.form.name')}</FieldLabel>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(event) => setData('name', event.target.value)}
                                    placeholder={__('muscle_group_pages.form.name_placeholder')}
                                    aria-invalid={!!errors.name}
                                    required
                                />
                                {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                            </Field>

                            <div className="flex flex-wrap gap-2">
                                <Button type="submit" disabled={processing}>
                                    {editingMuscleGroupId === null
                                        ? __('muscle_group_pages.form.create_action')
                                        : __('muscle_group_pages.form.update_action')}
                                </Button>
                                {editingMuscleGroupId !== null && (
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => {
                                            setEditingMuscleGroupId(null);
                                            reset();
                                        }}
                                    >
                                        {__('muscle_group_pages.form.cancel_action')}
                                    </Button>
                                )}
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>{__('muscle_group_pages.index.table.title')}</CardTitle>
                        <CardDescription>
                            {__('muscle_group_pages.index.table.description', { count: muscleGroups.length })}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>{__('muscle_group_pages.index.table.columns.name')}</TableHead>
                                    <TableHead>{__('muscle_group_pages.index.table.columns.exercises')}</TableHead>
                                    <TableHead className="text-right">
                                        {__('muscle_group_pages.index.table.columns.actions')}
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {muscleGroups.map((muscleGroup) => (
                                    <TableRow key={muscleGroup.id}>
                                        <TableCell className="font-medium">{muscleGroup.name}</TableCell>
                                        <TableCell>{muscleGroup.exercises_count ?? 0}</TableCell>
                                        <TableCell>
                                            <div className="flex justify-end gap-2">
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    onClick={() => {
                                                        setEditingMuscleGroupId(muscleGroup.id);
                                                        setData('name', muscleGroup.name);
                                                    }}
                                                >
                                                    {__('muscle_group_pages.index.table.edit')}
                                                </Button>
                                                <Button
                                                    size="sm"
                                                    variant="destructive"
                                                    render={
                                                        <Link
                                                            href={destroy(muscleGroup.id)}
                                                            method="delete"
                                                            as="button"
                                                        >
                                                            {__('muscle_group_pages.index.table.delete')}
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
