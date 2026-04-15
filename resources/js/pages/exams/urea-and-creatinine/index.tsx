import UreaAndCreatinineChart from '@/components/application/charts/urea-and-creatinine.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/urea-and-creatinine/urea-and-creatinine-columns';
import { create, index } from '@/routes/urea-and-creatinine';
import type { ChartData } from '@/types';
import type { UreaAndCreatinine } from '@/types/application/exams/urea-and-creatinine';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    ureaAndCreatinines: PaginationMeta<UreaAndCreatinine[]>;
    chartData: ChartData[];
};

export default function Index({ ureaAndCreatinines, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Urea and Creatinine Exams',
        description: 'View and analyze Urea and Creatinine results for patients',
        breadcrumbs: [{ title: 'Urea and Creatinine Exams', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = ureaAndCreatinines;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title="Urea and Creatinine Exams" />
            <h1 className="sr-only">Urea and Creatinine Exams</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <UreaAndCreatinineChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
