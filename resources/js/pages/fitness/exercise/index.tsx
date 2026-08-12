import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { create, destroy, edit, index } from '@/routes/exercises';
import type { CatalogExercise } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ModalLink } from '@inertiaui/modal-react';
import { Fragment } from 'react';

type Props = {
    exercises: CatalogExercise[];
};

export default function Index({ exercises }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('exercise_pages.index.title'),
        description: __('exercise_pages.index.description'),
        breadcrumbs: [{ title: __('exercise_pages.index.breadcrumbs.current'), href: index() }],
    });

    return (
        <Fragment>
            <Head title={__('exercise_pages.index.head_title')} />
            <h1 className="sr-only">{__('exercise_pages.index.head_title')}</h1>

            <Card>
                <CardHeader className="grid-rows-[auto_auto] items-start gap-2 border-b md:grid-cols-[1fr_auto]">
                    <div className="w-max space-y-1">
                        <CardTitle>{__('exercise_pages.index.table.title')}</CardTitle>
                        <CardDescription>
                            {__('exercise_pages.index.table.description', { count: exercises.length })}
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
                                {__('exercise_pages.form.create_action')}
                            </ModalLink>
                        }
                    />
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
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                render={
                                                    <ModalLink
                                                        href={edit(exercise.id).url}
                                                        method={edit(exercise.id).method}
                                                        as="button"
                                                    >
                                                        {__('exercise_pages.index.table.edit')}
                                                    </ModalLink>
                                                }
                                            />
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
        </Fragment>
    );
}
