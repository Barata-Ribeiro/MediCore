import AppearanceTabs from '@/components/helpers/appearance-tabs';
import Heading from '@/components/helpers/heading';
import SettingsLayout from '@/layouts/settings/layout';
import { edit as editAppearance } from '@/routes/appearance';
import { Head } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function Appearance() {
    return (
        <Fragment>
            <Head title="Appearance settings" />

            <h1 className="sr-only">Appearance settings</h1>

            <SettingsLayout>
                <div className="space-y-6">
                    <Heading
                        variant="small"
                        title="Appearance settings"
                        description="Update your account's appearance settings"
                    />
                    <AppearanceTabs />
                </div>
            </SettingsLayout>
        </Fragment>
    );
}

Appearance.layout = {
    breadcrumbs: [
        {
            title: 'Appearance settings',
            href: editAppearance(),
        },
    ],
};
