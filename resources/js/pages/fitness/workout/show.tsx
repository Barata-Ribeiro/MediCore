import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyDescription, EmptyHeader, EmptyTitle } from '@/components/ui/empty';
import { Separator } from '@/components/ui/separator';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { edit, index, show } from '@/routes/workouts';
import type { WorkoutExerciseResource, WorkoutResource } from '@/types/application/fitness/workout';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps, usePage } from '@inertiajs/react';
import { ArrowLeftIcon, DumbbellIcon, PencilIcon } from 'lucide-react';

export default function Show({ workout }: Readonly<{ workout: WorkoutResource }>) {
    const { __ } = lang();
    const { auth } = usePage().props;
    const locale = auth.locale?.replace('_', '-') ?? 'pt-BR';
    const formatDate = (value: string | null) =>
        value
            ? new Intl.DateTimeFormat(locale, { dateStyle: 'medium', timeZone: 'UTC' }).format(
                  new Date(value.slice(0, 10) + 'T00:00:00Z'),
              )
            : '—';
    const seconds = (value: number | null) =>
        value === null ? '—' : __('workout_pages.show.seconds_label', { seconds: value });
    const load = (exercise: WorkoutExerciseResource) =>
        exercise.load === null
            ? '—'
            : `${new Intl.NumberFormat(locale).format(Number(exercise.load))} ${exercise.load_unit}`;
    const totalExercises = workout.sections.reduce((sum, section) => sum + section.exercises.length, 0);

    setLayoutProps({
        title: __('workout_pages.show.title'),
        description: __('workout_pages.show.description'),
        breadcrumbs: [
            { title: __('workout_pages.show.breadcrumbs.index'), href: index() },
            { title: __('workout_pages.show.breadcrumbs.current'), href: show(workout.id) },
        ],
    });

    return (
        <div className="mx-auto flex max-w-7xl flex-col gap-6">
            <Head title={__('workout_pages.show.head_title')} />
            <div className="flex flex-wrap items-center justify-between gap-3">
                <Button nativeButton={false} variant="outline" render={<Link href={index()} />}>
                    <ArrowLeftIcon data-icon="inline-start" />
                    {__('workout_pages.shared.back')}
                </Button>
                <Button nativeButton={false} render={<Link href={edit(workout.id)} />}>
                    <PencilIcon data-icon="inline-start" />
                    {__('workout_pages.show.edit')}
                </Button>
            </div>
            <Card>
                <CardHeader>
                    <div className="flex flex-wrap items-start justify-between gap-4">
                        <div className="flex flex-col gap-2">
                            <p className="text-muted-foreground flex items-center gap-2 text-sm">
                                <DumbbellIcon className="size-4" aria-hidden />
                                {__('workout_pages.show.title')} #{workout.id}
                            </p>
                            <h1 className="font-heading text-2xl font-semibold tracking-tight sm:text-3xl">
                                {workout.goal || __('workout_pages.shared.untitled')}
                            </h1>
                            <CardDescription>
                                {workout.method || __('workout_pages.show.personal_plan')}
                            </CardDescription>
                        </div>
                        <Badge variant={workout.is_active ? 'default' : 'secondary'}>
                            {__('workout_pages.shared.' + (workout.is_active ? 'active_status' : 'inactive_status'))}
                        </Badge>
                    </div>
                </CardHeader>
                <CardContent className="flex flex-col gap-5">
                    <Separator />
                    <dl className="grid grid-cols-2 gap-5 lg:grid-cols-4">
                        {(
                            [
                                ['filled_at', formatDate(workout.filled_at)],
                                ['next_change_at', formatDate(workout.next_change_at)],
                                ['rest_between_sets', seconds(workout.rest_between_sets)],
                                ['rest_between_exercises', seconds(workout.rest_between_exercises)],
                            ] as const
                        ).map(([label, value]) => (
                            <div key={label} className="flex flex-col gap-1">
                                <dt className="text-muted-foreground text-sm">{__('workout_pages.form.' + label)}</dt>
                                <dd className="font-medium tabular-nums">{value}</dd>
                            </div>
                        ))}
                    </dl>
                    <div className="flex flex-wrap gap-2">
                        <Badge variant="outline">
                            {__('workout_pages.show.sections_badge', { count: workout.sections.length })}
                        </Badge>
                        <Badge variant="outline">
                            {__('workout_pages.form.exercise_count_badge', { count: totalExercises })}
                        </Badge>
                    </div>
                </CardContent>
            </Card>
            {workout.sections.length > 1 && (
                <nav aria-label={__('workout_pages.show.section_navigation')} className="flex flex-wrap gap-2">
                    {workout.sections.map((section) => (
                        <Button
                            key={section.id}
                            nativeButton={false}
                            variant="outline"
                            size="sm"
                            render={<a href={`#section-${section.id}`} />}
                        >
                            {section.name}
                        </Button>
                    ))}
                </nav>
            )}
            {workout.sections.length === 0 && (
                <Empty className="border">
                    <EmptyHeader>
                        <EmptyTitle>{__('workout_pages.show.empty_sections_title')}</EmptyTitle>
                        <EmptyDescription>{__('workout_pages.show.empty_sections_description')}</EmptyDescription>
                    </EmptyHeader>
                    <Button nativeButton={false} render={<Link href={edit(workout.id)} />}>
                        {__('workout_pages.show.edit')}
                    </Button>
                </Empty>
            )}
            {workout.sections.map((section) => (
                <Card key={section.id} id={`section-${section.id}`} className="scroll-mt-6">
                    <CardHeader>
                        <div className="flex items-center justify-between gap-3">
                            <CardTitle>{section.name}</CardTitle>
                            <Badge variant="secondary">
                                {__('workout_pages.form.exercise_count_badge', { count: section.exercises.length })}
                            </Badge>
                        </div>
                        <CardDescription>{__('workout_pages.show.execution_order')}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        {section.exercises.length === 0 ? (
                            <Empty>
                                <EmptyHeader>
                                    <EmptyTitle>{__('workout_pages.show.empty_exercises')}</EmptyTitle>
                                </EmptyHeader>
                            </Empty>
                        ) : (
                            <>
                                <div className="hidden md:block">
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                {['order', 'exercise', 'sets', 'reps', 'load', 'rest'].map((column) => (
                                                    <TableHead key={column}>
                                                        {__('workout_pages.show.table.' + column)}
                                                    </TableHead>
                                                ))}
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {section.exercises.map((exercise) => (
                                                <TableRow key={exercise.id}>
                                                    <TableCell>
                                                        <Badge variant="outline">{exercise.order}</Badge>
                                                        {exercise.code && (
                                                            <p className="text-muted-foreground mt-1 text-xs">
                                                                {exercise.code}
                                                            </p>
                                                        )}
                                                    </TableCell>
                                                    <TableCell className="min-w-48 whitespace-normal">
                                                        <div className="flex flex-col gap-1">
                                                            <span className="font-medium">
                                                                {exercise.exercise?.name ||
                                                                    __('workout_pages.shared.not_informed')}
                                                            </span>
                                                            {exercise.muscle_group && (
                                                                <span className="text-muted-foreground text-xs">
                                                                    {exercise.muscle_group.name}
                                                                </span>
                                                            )}
                                                            {exercise.notes && (
                                                                <p className="text-muted-foreground max-w-lg text-sm whitespace-pre-wrap">
                                                                    {exercise.notes}
                                                                </p>
                                                            )}
                                                        </div>
                                                    </TableCell>
                                                    <TableCell className="tabular-nums">{exercise.sets}</TableCell>
                                                    <TableCell className="tabular-nums">{exercise.reps}</TableCell>
                                                    <TableCell className="tabular-nums">{load(exercise)}</TableCell>
                                                    <TableCell className="tabular-nums">
                                                        {seconds(exercise.rest_seconds ?? workout.rest_between_sets)}
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                        </TableBody>
                                    </Table>
                                </div>
                                <div className="flex flex-col gap-4 md:hidden">
                                    {section.exercises.map((exercise) => (
                                        <Card key={exercise.id} size="sm">
                                            <CardHeader>
                                                <CardTitle>
                                                    <span className="flex items-start gap-2">
                                                        <Badge variant="outline">{exercise.order}</Badge>
                                                        {exercise.exercise?.name ||
                                                            __('workout_pages.shared.not_informed')}
                                                    </span>
                                                </CardTitle>
                                                <CardDescription>
                                                    {[exercise.code, exercise.muscle_group?.name]
                                                        .filter(Boolean)
                                                        .join(' · ')}
                                                </CardDescription>
                                            </CardHeader>
                                            <CardContent className="flex flex-col gap-3">
                                                <dl className="grid grid-cols-2 gap-3">
                                                    {(
                                                        [
                                                            ['sets', exercise.sets],
                                                            ['reps', exercise.reps],
                                                            ['load', load(exercise)],
                                                            [
                                                                'rest',
                                                                seconds(
                                                                    exercise.rest_seconds ?? workout.rest_between_sets,
                                                                ),
                                                            ],
                                                        ] as const
                                                    ).map(([label, value]) => (
                                                        <div key={label}>
                                                            <dt className="text-muted-foreground text-xs">
                                                                {__('workout_pages.show.table.' + label)}
                                                            </dt>
                                                            <dd className="font-medium tabular-nums">{value}</dd>
                                                        </div>
                                                    ))}
                                                </dl>
                                                {exercise.notes && (
                                                    <>
                                                        <Separator />
                                                        <p className="text-muted-foreground text-sm whitespace-pre-wrap">
                                                            {exercise.notes}
                                                        </p>
                                                    </>
                                                )}
                                            </CardContent>
                                        </Card>
                                    ))}
                                </div>
                            </>
                        )}
                    </CardContent>
                </Card>
            ))}
        </div>
    );
}
