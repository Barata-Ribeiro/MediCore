import LipidProfileChart from '@/components/application/charts/lipid-profile.chart';
import { EmptyChartData } from '@/components/common/empty-chart-data';
import { DataTable } from '@/components/table/data-table';
import { useLipidProfileColumns } from '@/pages/exams/lipid-profile/lipid-profile-columns';
import { create, index } from '@/routes/lipid-profile';
import type { ChartData } from '@/types';
import type { LipidProfile } from '@/types/application/exams/lipid-profile';
import type { PaginationMeta } from '@/types/application/metadata';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    lipidProfiles: PaginationMeta<LipidProfile[]>;
    chartData: ChartData[];
};

export default function Index({ lipidProfiles, chartData }: Readonly<Props>) {
    const { __ } = lang();
    const columns = useLipidProfileColumns();

    setLayoutProps({
        title: __('lipid_profile_pages.index.title'),
        description: __('lipid_profile_pages.index.description'),
        breadcrumbs: [{ title: __('lipid_profile_pages.index.breadcrumbs.current'), href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = lipidProfiles;

    console.log({ url, data, pagination, chartData });

    return (
        <Fragment>
            <Head title={__('lipid_profile_pages.index.head_title')} />
            <h1 className="sr-only">{__('lipid_profile_pages.index.head_title')}</h1>

            <div className="space-y-6">
                {chartData.length > 0 ? (
                    <LipidProfileChart chartData={chartData} total={pagination.total} />
                ) : (
                    <EmptyChartData createRoute={create()} />
                )}

                <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
            </div>
        </Fragment>
    );
}
