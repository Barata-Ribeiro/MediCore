import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/complete-blood-count/complete-blood-count-columns';
import { create, index } from '@/routes/complete-blood-count';
import type { CompleteBloodCount, CompleteBloodCountChartData } from '@/types/application/exams/complete-blood-count';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    completeBloodCount: PaginationMeta<CompleteBloodCount[]>;
    chartData: CompleteBloodCountChartData[];
};

export default function CompleteBloodCount({ completeBloodCount, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Complete Blood Count',
        description: 'View and analyze complete blood count results for patients',
        breadcrumbs: [{ title: 'Complete Blood Count', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = completeBloodCount;

    console.log({ url, chartData });

    return (
        <Fragment>
            <Head title="Complete Blood Count" />
            <h1 className="sr-only">Complete Blood Count</h1>

            <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
        </Fragment>
    );
}
