import { edit } from '@/actions/App/Http/Controllers/Settings/ProfileController';
import AppLanguageSelector from '@/components/application/app-language-selector';
import Heading from '@/components/common/heading';
import BaseAccountUpdateForm from '@/components/forms/settings/base-account-update.form';
import PersonalProfileManagerForm from '@/components/forms/settings/personal-profile-manager.form';
import DeleteUser from '@/components/helpers/delete-user';
import { Separator } from '@/components/ui/separator';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react';

export default function Profile({
    mustVerifyEmail,
    status,
}: Readonly<{
    mustVerifyEmail: boolean;
    status?: string;
}>) {
    setLayoutProps({
        breadcrumbs: [
            {
                title: 'Profile settings',
                href: edit(),
            },
        ],
    });

    return (
        <Fragment>
            <Head title="Profile settings" />
            <h1 className="sr-only">Profile settings</h1>

            <div className="space-y-6">
                <Heading variant="small" title="Profile information" description="Update your name and email address" />

                <BaseAccountUpdateForm mustVerifyEmail={mustVerifyEmail} status={status} />
            </div>

            <Separator className="my-6" />

            <div className="space-y-6">
                <Heading variant="small" title="Language" description="Update your language preferences" />

                <AppLanguageSelector />
            </div>

            <Separator className="mb-6" />

            <div className="space-y-6">
                <Heading variant="small" title="Personal Information" description="Update your personal details" />

                <PersonalProfileManagerForm />
            </div>

            <DeleteUser />
        </Fragment>
    );
}
