import { DataTable } from '@/components/table/data-table';
import { useExerciseColumns } from '@/pages/fitness/exercise/exercise-columns';
import { create, index } from '@/routes/exercises';
import type { CatalogExercise } from '@/types/application/fitness/catalog';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    exercises: PaginationMeta<CatalogExercise[]>;
};

export default function Index({ exercises }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useExerciseColumns();

    setLayoutProps({
        title: __('exercise_pages.index.title'),
        description: __('exercise_pages.index.description'),
        breadcrumbs: [{ title: __('exercise_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { data, ...pagination } = exercises;

    return (
        <Fragment>
            <Head title={__('exercise_pages.index.head_title')} />
            <h1 className="sr-only">{__('exercise_pages.index.head_title')}</h1>
            <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
        </Fragment>
    );
}
