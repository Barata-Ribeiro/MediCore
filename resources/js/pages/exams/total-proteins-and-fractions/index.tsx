import TotalProteinsAndFractionsChart from '@/components/application/charts/total-proteins-and-fractions-chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useTotalProteinsAndFractionsColumns } from '@/pages/exams/total-proteins-and-fractions/tp-and-fractions-columns';
import { create, index } from '@/routes/total_proteins_and_fractions';
import type { ChartData } from '@/types';
import type { TotalProteinsAndFractions } from '@/types/application/exams/total-proteins-and-fractions';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    totalProteinsAndFractions: PaginationMeta<TotalProteinsAndFractions[]>;
    chartData: ChartData[];
};

export default function Index({ totalProteinsAndFractions, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useTotalProteinsAndFractionsColumns();

    setLayoutProps({
        title: __('total_proteins_and_fractions_pages.index.title'),
        description: __('total_proteins_and_fractions_pages.index.description'),
        breadcrumbs: [
            {
                title: __('total_proteins_and_fractions_pages.index.breadcrumbs.current'),
                href: index(),
            },
        ],
    });

    const { data, ...pagination } = totalProteinsAndFractions;

    return (
        <Fragment>
            <Head title={__('total_proteins_and_fractions_pages.index.head_title')} />
            <h1 className="sr-only">{__('total_proteins_and_fractions_pages.index.head_title')}</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <TotalProteinsAndFractionsChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
