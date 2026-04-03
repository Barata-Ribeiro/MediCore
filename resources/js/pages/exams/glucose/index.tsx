import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/glucose/glucose-columns';
import { create, index } from '@/routes/glucose';
import type { ChartData } from '@/types';
import type { Glucose } from '@/types/application/exams/glucose';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    glucoses: PaginationMeta<Glucose[]>;
    chartData: ChartData[];
};

export default function Index({ glucoses, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Glucose',
        description: 'View and analyze glucose results for patients',
        breadcrumbs: [{ title: 'Glucose', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = glucoses;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title="Glucose" />
            <h1 className="sr-only">Glucose</h1>

            <div className="space-y-6">
                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
