import WorkoutRegistryForm from '@/components/forms/fitness/workout-registry.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { create, index } from '@/routes/workouts';
import type { WorkoutFormOptions } from '@/types/application/fitness/workout';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type WorkoutCreateProps = {
    formOptions: WorkoutFormOptions;
};

export default function Create({ formOptions }: Readonly<WorkoutCreateProps>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('workout_pages.create.title'),
        description: __('workout_pages.create.description'),
        breadcrumbs: [
            { title: __('workout_pages.create.breadcrumbs.index'), href: index() },
            { title: __('workout_pages.create.breadcrumbs.current'), href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title={__('workout_pages.create.head_title')} />
            <h1 className="sr-only">{__('workout_pages.create.head_title')}</h1>

            <Card>
                <CardHeader className="space-y-4">
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        render={
                            <Link href={index()} prefetch="hover" viewTransition>
                                <ArrowLeftIcon aria-hidden size={14} />
                                {__('workout_pages.shared.back')}
                            </Link>
                        }
                    />
                    <div>
                        <CardTitle>{__('workout_pages.create.title')}</CardTitle>
                        <CardDescription>{__('workout_pages.create.description')}</CardDescription>
                    </div>
                </CardHeader>

                <CardContent>
                    <WorkoutRegistryForm formOptions={formOptions} />
                </CardContent>
            </Card>
        </Fragment>
    );
}
