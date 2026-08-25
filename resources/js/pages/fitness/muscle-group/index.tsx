import Heading from '@/components/common/heading';
import { DataTable } from '@/components/table/data-table';
import { useMuscleGroupColumns } from '@/pages/fitness/muscle-group/muscle-group-columns';
import { create, index } from '@/routes/muscle-groups';
import type { CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    muscleGroups: PaginationMeta<CatalogMuscleGroup[]>;
};

export default function Index({ muscleGroups }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useMuscleGroupColumns();

    setLayoutProps({
        title: __('muscle_group_pages.index.title'),
        description: __('muscle_group_pages.index.description'),
        breadcrumbs: [{ title: __('muscle_group_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { data, ...pagination } = muscleGroups;

    return (
        <Fragment>
            <Head title={__('muscle_group_pages.index.head_title')} />
            <h1 className="sr-only">{__('muscle_group_pages.index.head_title')}</h1>

            <Heading
                title={__('muscle_group_pages.index.table.title')}
                description={__('muscle_group_pages.index.table.description', { count: data.length })}
            />

            <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} isModal />
        </Fragment>
    );
}
