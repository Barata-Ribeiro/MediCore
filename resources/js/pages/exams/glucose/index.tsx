import GlucoseChart from '@/components/application/charts/glucose.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useGlucoseColumns } from '@/pages/exams/glucose/glucose-columns';
import { create, index } from '@/routes/glucose';
import type { ChartData } from '@/types';
import type { Glucose } from '@/types/application/exams/glucose';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    glucoses: PaginationMeta<Glucose[]>;
    chartData: ChartData[];
};

export default function Index({ glucoses, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useGlucoseColumns();

    setLayoutProps({
        title: __('glucose_pages.index.title'),
        description: __('glucose_pages.index.description'),
        breadcrumbs: [{ title: __('glucose_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = glucoses;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title={__('glucose_pages.index.head_title')} />
            <h1 className="sr-only">{__('glucose_pages.index.head_title')}</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <GlucoseChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
