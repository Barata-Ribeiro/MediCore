import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyDescription, EmptyHeader, EmptyTitle } from '@/components/ui/empty';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { create, destroy, edit, index, show } from '@/routes/workouts';
import type { WorkoutResource } from '@/types/application/fitness/workout';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowRightIcon, PlusIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type WorkoutIndexProps = {
    workouts: PaginationMeta<WorkoutResource[]>;
};

export default function Index({ workouts }: Readonly<WorkoutIndexProps>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('workout_pages.index.title'),
        description: __('workout_pages.index.description'),
        breadcrumbs: [{ title: __('workout_pages.index.breadcrumbs.current'), href: index() }],
    });

    return (
        <Fragment>
            <Head title={__('workout_pages.index.head_title')} />

            <Card>
                <CardHeader className="flex flex-row items-center justify-between gap-4">
                    <div>
                        <CardTitle>{__('workout_pages.index.title')}</CardTitle>
                        <CardDescription>
                            {__('workout_pages.index.summary', {
                                count: workouts.total,
                            })}
                        </CardDescription>
                    </div>

                    <Button render={<Link href={create()} prefetch="hover" viewTransition />}>
                        <PlusIcon aria-hidden size={14} />
                        {__('workout_pages.index.new_workout')}
                    </Button>
                </CardHeader>

                <CardContent>
                    {workouts.data.length === 0 ? (
                        <Empty className="border">
                            <EmptyHeader>
                                <EmptyTitle>{__('workout_pages.index.empty_title')}</EmptyTitle>
                                <EmptyDescription>{__('workout_pages.index.empty_description')}</EmptyDescription>
                            </EmptyHeader>
                            <Button render={<Link href={create()} prefetch="hover" />}>
                                {__('workout_pages.index.empty_cta')}
                            </Button>
                        </Empty>
                    ) : (
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>{__('workout_pages.index.table.goal')}</TableHead>
                                    <TableHead>{__('workout_pages.index.table.method')}</TableHead>
                                    <TableHead>{__('workout_pages.index.table.sections')}</TableHead>
                                    <TableHead>{__('workout_pages.index.table.exercises')}</TableHead>
                                    <TableHead>{__('workout_pages.index.table.status')}</TableHead>
                                    <TableHead className="text-right">
                                        {__('workout_pages.index.table.actions')}
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {workouts.data.map((workout) => {
                                    const totalExercises = workout.sections.reduce(
                                        (exerciseCount, section) => exerciseCount + section.exercises.length,
                                        0,
                                    );

                                    return (
                                        <TableRow key={workout.id}>
                                            <TableCell className="font-medium">
                                                {workout.goal || __('workout_pages.shared.not_informed')}
                                            </TableCell>
                                            <TableCell>
                                                {workout.method || __('workout_pages.shared.not_informed')}
                                            </TableCell>
                                            <TableCell>{workout.sections.length}</TableCell>
                                            <TableCell>{totalExercises}</TableCell>
                                            <TableCell>
                                                <Badge variant={workout.is_active ? 'default' : 'secondary'}>
                                                    {workout.is_active
                                                        ? __('workout_pages.shared.active_status')
                                                        : __('workout_pages.shared.inactive_status')}
                                                </Badge>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center justify-end gap-2">
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        render={
                                                            <Link
                                                                href={show(workout.id)}
                                                                prefetch="hover"
                                                                viewTransition
                                                            >
                                                                {__('workout_pages.index.actions.view')}
                                                            </Link>
                                                        }
                                                    />
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        render={
                                                            <Link
                                                                href={edit(workout.id)}
                                                                prefetch="hover"
                                                                viewTransition
                                                            >
                                                                {__('workout_pages.index.actions.edit')}
                                                            </Link>
                                                        }
                                                    />
                                                    <Button
                                                        size="sm"
                                                        variant="destructive"
                                                        render={
                                                            <Link
                                                                href={destroy(workout.id)}
                                                                method="delete"
                                                                as="button"
                                                            >
                                                                {__('workout_pages.index.actions.delete')}
                                                            </Link>
                                                        }
                                                    />
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    );
                                })}
                            </TableBody>
                        </Table>
                    )}

                    {workouts.next_page_url && (
                        <div className="mt-4 flex justify-end">
                            <Button
                                variant="ghost"
                                render={
                                    <Link href={workouts.next_page_url} preserveScroll preserveState viewTransition />
                                }
                            >
                                {__('workout_pages.index.next_page')}
                                <ArrowRightIcon aria-hidden size={14} />
                            </Button>
                        </div>
                    )}
                </CardContent>
            </Card>
        </Fragment>
    );
}
