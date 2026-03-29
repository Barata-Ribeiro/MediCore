import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/lipid-profile/lipid-profile-columns';
import { index } from '@/routes/lipid-profile';
import type { LipidProfile, LipidProfileChartData } from '@/types/application/exams/lipid-profile';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    lipidProfile: PaginationMeta<LipidProfile[]>;
    chartData: LipidProfileChartData[];
};

export default function LipidProfile({ lipidProfile, chartData }: Readonly<Props>) {
    const { url } = usePage();
    const { data, ...pagination } = lipidProfile;

    return (
        <Fragment>
            <Head title="Lipid Profile" />
            <h1 className="sr-only">Lipid Profile</h1>

            <div className="px-4 py-6">
                <DataTable columns={columns} data={data} pagination={pagination} />
            </div>
        </Fragment>
    );
}

LipidProfile.layout = {
    breadcrumbs: [
        {
            title: 'Lipid Profile',
            href: index(),
        },
    ],
};
