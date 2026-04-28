import VitaminD3Chart from '@/components/application/charts/vitamin-d3.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useVitaminD3Columns } from '@/pages/exams/vitamin-d3/vitamin-d3-columns';
import { create, index } from '@/routes/vitamin-d3';
import type { ChartData } from '@/types';
import type { VitaminD3 } from '@/types/application/exams/vitamin-d3';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    vitaminD3s: PaginationMeta<VitaminD3[]>;
    chartData: ChartData[];
};

export default function Index({ vitaminD3s, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useVitaminD3Columns();

    setLayoutProps({
        title: __('vitamin_d3_pages.index.title'),
        description: __('vitamin_d3_pages.index.description'),
        breadcrumbs: [{ title: __('vitamin_d3_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = vitaminD3s;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title={__('vitamin_d3_pages.index.head_title')} />
            <h1 className="sr-only">{__('vitamin_d3_pages.index.head_title')}</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <VitaminD3Chart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
