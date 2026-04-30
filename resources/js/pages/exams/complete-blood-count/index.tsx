import CbcCountChart from '@/components/application/charts/cbc-count.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useCompleteBloodCountColumns } from '@/pages/exams/complete-blood-count/complete-blood-count-columns';
import { create, index } from '@/routes/complete-blood-count';
import type { ChartData } from '@/types';
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    completeBloodCounts: PaginationMeta<CompleteBloodCount[]>;
    chartData: ChartData[];
};

export default function Index({ completeBloodCounts, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useCompleteBloodCountColumns();

    setLayoutProps({
        title: __('complete_blood_count_pages.index.title'),
        description: __('complete_blood_count_pages.index.description'),
        breadcrumbs: [{ title: __('complete_blood_count_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = completeBloodCounts;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title={__('complete_blood_count_pages.index.head_title')} />
            <h1 className="sr-only">{__('complete_blood_count_pages.index.head_title')}</h1>

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
