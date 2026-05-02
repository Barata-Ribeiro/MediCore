import UltrasensitiveTshChart from '@/components/application/charts/ultrasensitive-tsh.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useUltrasensitiveTshColumns } from '@/pages/exams/ultrasensitive-tsh/ultrasensitive-tsh-columns';
import { create, index } from '@/routes/ultrasensitive-tsh';
import type { ChartData } from '@/types';
import type { UltrasensitiveTsh } from '@/types/application/exams/ultrasensitive-tsh';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    ultrasensitiveTshs: PaginationMeta<UltrasensitiveTsh[]>;
    chartData: ChartData[];
};

export default function Index({ ultrasensitiveTshs, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useUltrasensitiveTshColumns();

    setLayoutProps({
        title: __('ultrasensitive_tsh_pages.index.title'),
        description: __('ultrasensitive_tsh_pages.index.description'),
        breadcrumbs: [{ title: __('ultrasensitive_tsh_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = ultrasensitiveTshs;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title={__('ultrasensitive_tsh_pages.index.head_title')} />
            <h1 className="sr-only">{__('ultrasensitive_tsh_pages.index.head_title')}</h1>

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
