import { DataTable } from '@/components/table/data-table';
import { columns } from '@/pages/exams/lipid-profile/lipid-profile-columns';
import { create, index } from '@/routes/lipid-profile';
import type { LipidProfile, LipidProfileChartData } from '@/types/application/exams/lipid-profile';
import type { PaginationMeta } from '@/types/application/metadata';
import { Head, setLayoutProps, usePage } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    lipidProfile: PaginationMeta<LipidProfile[]>;
    chartData: LipidProfileChartData[];
};

export default function LipidProfile({ lipidProfile, chartData }: Readonly<Props>) {
    setLayoutProps({
        title: 'Lipid profiles',
        description: 'View and analyze lipid profile results for patients',
        breadcrumbs: [{ title: 'Lipid Profiles', href: index() }],
    });

    const { url } = usePage();
    const { data, ...pagination } = lipidProfile;

    console.log({ url, chartData });

    return (
        <Fragment>
            <Head title="Lipid Profile" />
            <h1 className="sr-only">Lipid Profile</h1>

            <DataTable columns={columns} data={data} pagination={pagination} createRoute={create()} />
        </Fragment>
    );
}
