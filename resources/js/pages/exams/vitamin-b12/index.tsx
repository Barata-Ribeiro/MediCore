import VitaminB12Chart from '@/components/application/charts/vitamin-b12.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useVitaminB12Columns } from '@/pages/exams/vitamin-b12/vitamin-b12-columns';
import { create, index } from '@/routes/vitamin-b12';
import type { ChartData } from '@/types';
import type { VitaminB12 } from '@/types/application/exams/vitamin-b12';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    vitaminB12s: PaginationMeta<VitaminB12[]>;
    chartData: ChartData[];
};

export default function Index({ vitaminB12s, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useVitaminB12Columns();

    setLayoutProps({
        title: __('vitamin_b12_pages.index.title'),
        description: __('vitamin_b12_pages.index.description'),
        breadcrumbs: [{ title: __('vitamin_b12_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = vitaminB12s;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title={__('vitamin_b12_pages.index.head_title')} />
            <h1 className="sr-only">{__('vitamin_b12_pages.index.head_title')}</h1>

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
