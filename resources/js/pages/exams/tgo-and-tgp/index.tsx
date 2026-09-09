import TgoAndTgpChart from '@/components/application/charts/tgo-and-tgp.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useTgoAndTgpColumns } from '@/pages/exams/tgo-and-tgp/tgo-and-tgp-columns';
import { create, index } from '@/routes/tgo-and-tgp';
import type { ChartData } from '@/types';
import type { TgoAndTgp } from '@/types/application/exams/tgo-and-tgp';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    tgoAndTgps: PaginationMeta<TgoAndTgp[]>;
    chartData: ChartData[];
};

export default function Index({ tgoAndTgps, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useTgoAndTgpColumns();

    setLayoutProps({
        title: __('tgo_and_tgp_pages.index.title'),
        description: __('tgo_and_tgp_pages.index.description'),
        breadcrumbs: [{ title: __('tgo_and_tgp_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { data, ...pagination } = tgoAndTgps;

    return (
        <Fragment>
            <Head title={__('tgo_and_tgp_pages.index.head_title')} />
            <h1 className="sr-only">{__('tgo_and_tgp_pages.index.head_title')}</h1>

            <div className="flex flex-col gap-6">
                {chartData.length > 0 ? (
                    <TgoAndTgpChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
