import DashboardBMICard from '@/components/helpers/dashboard/dashboard-bmi.card';
import DashboardExamsMadeCard from '@/components/helpers/dashboard/dashboard-exams-made.card';
import DashboardExamsSummaryCard from '@/components/helpers/dashboard/dashboard-exams-summary.card';
import DashboardGreetings from '@/components/helpers/dashboard/dashboard-greetings';
import DashboardMedicalFileCard from '@/components/helpers/dashboard/dashboard-medical-file.card';
import DashboardProfileCard from '@/components/helpers/dashboard/dashboard-profile.card';
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
    return (
        <Fragment>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <DashboardGreetings />

                <div className="grid gap-4 sm:grid-cols-2">
                    <DashboardProfileCard profile={data.profile} />

                    <div className="grid gap-4 lg:grid-cols-2">
                        <DashboardBMICard
                            bmi={data.medicalFile.bmi}
                            height={data.medicalFile.height}
                            weight={data.medicalFile.weight}
                        />

                        <DashboardMedicalFileCard medicalFile={data.medicalFile} />
                    </div>
                </div>

                <div className="grid gap-4 sm:grid-cols-3">
                    <DashboardExamsSummaryCard exams={data.exams} />
                    <DashboardExamsMadeCard exams={data.exams} />
                </div>
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
