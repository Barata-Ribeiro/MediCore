import UricAcidChart from '@/components/application/charts/uric-acid.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/uric-acid/uric-acid-columns';
import { create, index } from '@/routes/uric-acid';
import type { ChartData } from '@/types';
import type { UricAcid } from '@/types/application/exams/uric-acid';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    uricAcids: PaginationMeta<UricAcid[]>;
    chartData: ChartData[];
};

export default function Index({ uricAcids, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Uric Acid Exams',
        description: 'View and analyze Uric Acid results for patients',
        breadcrumbs: [{ title: 'Uric Acid Exams', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = uricAcids;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title="Uric Acid Exams" />
            <h1 className="sr-only">Uric Acid Exams</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <UricAcidChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
