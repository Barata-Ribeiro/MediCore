import CbcCountChart from '@/components/application/charts/cbc-count.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/complete-blood-count/complete-blood-count-columns';
import { create, index } from '@/routes/complete-blood-count';
import type { ChartData } from '@/types';
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    completeBloodCount: PaginationMeta<CompleteBloodCount[]>;
    chartData: ChartData[];
};

export default function CompleteBloodCount({ completeBloodCount, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Complete Blood Count',
        description: 'View and analyze complete blood count results for patients',
        breadcrumbs: [{ title: 'Complete Blood Count', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = completeBloodCount;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title="Complete Blood Count" />
            <h1 className="sr-only">Complete Blood Count</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <CbcCountChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
