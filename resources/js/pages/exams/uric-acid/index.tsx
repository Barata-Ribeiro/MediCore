import UricAcidChart from '@/components/application/charts/uric-acid.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useUricAcidColumns } from '@/pages/exams/uric-acid/uric-acid-columns';
import { create, index } from '@/routes/uric-acid';
import type { ChartData } from '@/types';
import type { UricAcid } from '@/types/application/exams/uric-acid';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    uricAcids: PaginationMeta<UricAcid[]>;
    chartData: ChartData[];
};

export default function Index({ uricAcids, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useUricAcidColumns();

    setLayoutProps({
        title: __('uric_acid_pages.index.title'),
        description: __('uric_acid_pages.index.description'),
        breadcrumbs: [{ title: __('uric_acid_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = uricAcids;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title={__('uric_acid_pages.index.head_title')} />
            <h1 className="sr-only">{__('uric_acid_pages.index.head_title')}</h1>

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
