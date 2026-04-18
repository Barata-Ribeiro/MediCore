import UltrasensitiveTshChart from '@/components/application/charts/ultrasensitive-tsh.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/ultrasensitive-tsh/ultrasensitive-tsh-columns';
import { create, index } from '@/routes/ultrasensitive-tsh';
import type { ChartData } from '@/types';
import type { UltrasensitiveTsh } from '@/types/application/exams/ultrasensitive-tsh';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    ultrasensitiveTshs: PaginationMeta<UltrasensitiveTsh[]>;
    chartData: ChartData[];
};

export default function Index({ ultrasensitiveTshs, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Ultrasensitive TSH Exams',
        description: 'View and analyze Ultrasensitive TSH results for patients',
        breadcrumbs: [{ title: 'Ultrasensitive TSH Exams', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = ultrasensitiveTshs;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title="Ultrasensitive TSH Exams" />
            <h1 className="sr-only">Ultrasensitive TSH Exams</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <UltrasensitiveTshChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
