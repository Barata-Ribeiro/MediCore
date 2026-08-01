import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyDescription, EmptyHeader, EmptyTitle } from '@/components/ui/empty';
import { Separator } from '@/components/ui/separator';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { edit, index, show } from '@/routes/workouts';
import type { WorkoutResource } from '@/types/application/fitness/workout';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon, PencilIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type WorkoutShowProps = {
    workout: WorkoutResource;
};

export default function Show({ workout }: Readonly<WorkoutShowProps>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('workout_pages.show.title'),
        description: __('workout_pages.show.description'),
        breadcrumbs: [
            { title: __('workout_pages.show.breadcrumbs.index'), href: index() },
            { title: __('workout_pages.show.breadcrumbs.current'), href: show(workout.id) },
        ],
    });

    return (
        <Fragment>
            <Head title={__('workout_pages.show.head_title')} />
            <h1 className="sr-only">{__('workout_pages.show.head_title')}</h1>

            <Card>
                <CardHeader className="space-y-4">
                    <div className="flex flex-wrap items-center justify-between gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            render={
                                <Link href={index()} prefetch="hover" viewTransition>
                                    <ArrowLeftIcon aria-hidden size={14} />
                                    {__('workout_pages.shared.back')}
                                </Link>
                            }
                        />

                        <Button
                            size="sm"
                            render={
                                <Link href={edit(workout.id)} prefetch="hover" viewTransition>
                                    <PencilIcon aria-hidden size={14} />
                                    {__('workout_pages.show.edit')}
                                </Link>
                            }
                        />
                    </div>

                    <div>
                        <CardTitle>{workout.goal || __('workout_pages.shared.not_informed')}</CardTitle>
                        <CardDescription>{workout.method || __('workout_pages.shared.not_informed')}</CardDescription>
                    </div>
                </CardHeader>

                <CardContent className="space-y-4">
                    <div className="flex flex-wrap items-center gap-2">
                        <Badge variant={workout.is_active ? 'default' : 'secondary'}>
                            {workout.is_active
                                ? __('workout_pages.shared.active_status')
                                : __('workout_pages.shared.inactive_status')}
                        </Badge>
                        <Badge variant="outline">
                            {__('workout_pages.show.sections_badge', { count: workout.sections.length })}
                        </Badge>
                    </div>

                    <Separator />

                    {workout.sections.length === 0 ? (
                        <Empty className="border">
                            <EmptyHeader>
                                <EmptyTitle>{__('workout_pages.show.empty_sections_title')}</EmptyTitle>
                                <EmptyDescription>
                                    {__('workout_pages.show.empty_sections_description')}
                                </EmptyDescription>
                            </EmptyHeader>
                        </Empty>
                    ) : (
                        workout.sections.map((section, sectionIndex) => (
                            <Card key={section.id} className="border bg-muted/10">
                                <CardHeader>
                                    <CardTitle>
                                        {__('workout_pages.show.section_label', {
                                            number: sectionIndex + 1,
                                        })}
                                    </CardTitle>
                                    <CardDescription>{section.name}</CardDescription>
                                </CardHeader>

                                <CardContent>
                                    {section.exercises.length === 0 ? (
                                        <p className="text-sm text-muted-foreground">
                                            {__('workout_pages.show.empty_exercises')}
                                        </p>
                                    ) : (
                                        <Table>
                                            <TableHeader>
                                                <TableRow>
                                                    <TableHead>{__('workout_pages.show.table.code')}</TableHead>
                                                    <TableHead>{__('workout_pages.show.table.exercise')}</TableHead>
                                                    <TableHead>{__('workout_pages.show.table.muscle_group')}</TableHead>
                                                    <TableHead>{__('workout_pages.show.table.sets_reps')}</TableHead>
                                                    <TableHead>{__('workout_pages.show.table.load')}</TableHead>
                                                    <TableHead>{__('workout_pages.show.table.rest')}</TableHead>
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody>
                                                {section.exercises.map((exercise) => (
                                                    <TableRow key={exercise.id}>
                                                        <TableCell>{exercise.code || '-'}</TableCell>
                                                        <TableCell>
                                                            {exercise.exercise?.name ||
                                                                __('workout_pages.shared.not_informed')}
                                                        </TableCell>
                                                        <TableCell>
                                                            {exercise.muscle_group?.name ||
                                                                __('workout_pages.shared.not_informed')}
                                                        </TableCell>
                                                        <TableCell>{`${exercise.sets} x ${exercise.reps}`}</TableCell>
                                                        <TableCell>
                                                            {exercise.load === null
                                                                ? '-'
                                                                : `${exercise.load} ${exercise.load_unit}`}
                                                        </TableCell>
                                                        <TableCell>
                                                            {exercise.rest_seconds === null
                                                                ? '-'
                                                                : __('workout_pages.show.seconds_label', {
                                                                      seconds: exercise.rest_seconds,
                                                                  })}
                                                        </TableCell>
                                                    </TableRow>
                                                ))}
                                            </TableBody>
                                        </Table>
                                    )}
                                </CardContent>
                            </Card>
                        ))
                    )}
                </CardContent>
            </Card>
        </Fragment>
    );
}
