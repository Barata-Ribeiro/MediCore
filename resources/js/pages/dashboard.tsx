import DashboardGreetings from '@/components/helpers/dashboard/dashboard-greetings';
import { dashboard } from '@/routes';
import { Head } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function Dashboard() {
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
