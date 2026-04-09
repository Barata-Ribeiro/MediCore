import VitaminB12Chart from '@/components/application/charts/vitamin-b12.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/vitamin-b12/vitamin-b12-columns';
import { create, index } from '@/routes/vitamin-b12';
import type { ChartData } from '@/types';
import type { VitaminB12 } from '@/types/application/exams/vitamin-b12';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    vitaminB12s: PaginationMeta<VitaminB12[]>;
    chartData: ChartData[];
};

export default function Index({ vitaminB12s, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Vitamin B12 Exams',
        description: 'View and analyze Vitamin B12 results for patients',
        breadcrumbs: [{ title: 'Vitamin B12 Exams', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = vitaminB12s;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title="Vitamin B12 Exams" />
            <h1 className="sr-only">Vitamin B12 Exams</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <VitaminB12Chart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
