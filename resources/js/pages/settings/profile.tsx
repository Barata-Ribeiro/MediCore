import { edit } from '@/actions/App/Http/Controllers/Settings/ProfileController';
import BaseAccountUpdateForm from '@/components/forms/settings/base-account-update.form';
import PersonalProfileManagerForm from '@/components/forms/settings/personal-profile-manager.form';
import DeleteUser from '@/components/helpers/delete-user';
import { Separator } from '@/components/ui/separator';
import { Head } from '@inertiajs/react';
import { Fragment } from 'react';

export default function Profile({
    mustVerifyEmail,
    status,
}: Readonly<{
    mustVerifyEmail: boolean;
    status?: string;
}>) {
    return (
        <Fragment>
            <Head title="Profile settings" />

            <h1 className="sr-only">Profile settings</h1>

            <BaseAccountUpdateForm mustVerifyEmail={mustVerifyEmail} status={status} />

            <Separator className="my-6" />

            <PersonalProfileManagerForm />

            <DeleteUser />
        </Fragment>
    );
}

Profile.layout = {
    breadcrumbs: [
        {
            title: 'Profile settings',
            href: edit(),
        },
    ],
};
