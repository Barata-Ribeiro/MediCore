import DashboardGreetings from '@/components/helpers/dashboard/dashboard-greetings';
import { dashboard } from '@/routes';
import type { MedicalFile } from '@/types/application/medical-file';
import type { Profile } from '@/types/application/profile';
import { Head } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    profile: Profile;
    medicalFile: MedicalFile;
    exams: Record<string, number>;
};

export default function Dashboard({ data }: Readonly<{ data: Props }>) {
    console.log(data);

    return (
        <Fragment>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <DashboardGreetings />
            </div>
        </Fragment>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
