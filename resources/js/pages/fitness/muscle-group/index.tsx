import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { create, destroy, edit, index } from '@/routes/muscle-groups';
import type { CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ModalLink } from '@inertiaui/modal-react';
import { Fragment } from 'react';

type Props = {
    muscleGroups: CatalogMuscleGroup[];
};

export default function Index({ muscleGroups }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('muscle_group_pages.index.title'),
        description: __('muscle_group_pages.index.description'),
        breadcrumbs: [{ title: __('muscle_group_pages.index.breadcrumbs.current'), href: index() }],
    });

    return (
        <Fragment>
            <Head title={__('muscle_group_pages.index.head_title')} />
            <h1 className="sr-only">{__('muscle_group_pages.index.head_title')}</h1>

            <Card>
                <CardHeader className="grid-rows-[auto_auto] items-start gap-2 border-b md:grid-cols-[1fr_auto]">
                    <div className="w-max space-y-1">
                        <CardTitle>{__('muscle_group_pages.index.table.title')}</CardTitle>
                        <CardDescription>
                            {__('muscle_group_pages.index.table.description', { count: muscleGroups.length })}
                        </CardDescription>
                    </div>
                    <Button
                        render={
                            <ModalLink
                                href={create().url}
                                className="mr-auto md:ml-auto"
                                method={create().method}
                                as="button"
                            >
                                {__('muscle_group_pages.form.create_action')}
                            </ModalLink>
                        }
                    />
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
                                                render={
                                                    <ModalLink
                                                        href={edit(muscleGroup.id).url}
                                                        method={edit(muscleGroup.id).method}
                                                        as="button"
                                                    >
                                                        {__('muscle_group_pages.index.table.edit')}
                                                    </ModalLink>
                                                }
                                            />
                                            <Button
                                                size="sm"
                                                variant="destructive"
                                                render={
                                                    <Link href={destroy(muscleGroup.id)} method="delete" as="button">
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
        </Fragment>
    );
}
