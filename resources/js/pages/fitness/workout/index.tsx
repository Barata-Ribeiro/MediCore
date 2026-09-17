import Heading from '@/components/common/heading';
import { DataTable } from '@/components/table/data-table';
import { useWorkoutColumns } from '@/pages/fitness/workout/workout-columns';
import { create, index } from '@/routes/workouts';
import type { WorkoutSummary } from '@/types/application/fitness/workout';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    workouts: PaginationMeta<WorkoutSummary[]>;
};

export default function Index({ workouts }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useWorkoutColumns();

    setLayoutProps({
        title: __('workout_pages.index.title'),
        description: __('workout_pages.index.description'),
        breadcrumbs: [{ title: __('workout_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { data, ...pagination } = workouts;

    return (
        <Fragment>
            <Head title={__('workout_pages.index.head_title')} />
            <h1 className="sr-only">{__('workout_pages.index.head_title')}</h1>

            <Heading
                title={__('workout_pages.index.table.title')}
                description={__('workout_pages.index.table.description', { count: pagination.total })}
            />

            <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
        </Fragment>
    );
}
