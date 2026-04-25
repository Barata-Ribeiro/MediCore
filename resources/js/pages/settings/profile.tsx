import { edit } from '@/actions/App/Http/Controllers/Settings/ProfileController';
import AppLanguageSelector from '@/components/application/app-language-selector';
import Heading from '@/components/common/heading';
import BaseAccountUpdateForm from '@/components/forms/settings/base-account-update.form';
import PersonalProfileManagerForm from '@/components/forms/settings/personal-profile-manager.form';
import DeleteUser from '@/components/helpers/delete-user';
import { Separator } from '@/components/ui/separator';
import { useIsomorphicLayoutEffect } from '@/hooks/use-isomorphic-layout-effect';
import useIsMounted from '@/hooks/use-mounted';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react';

export default function Profile({
    mustVerifyEmail,
    status,
}: Readonly<{
    mustVerifyEmail: boolean;
    status?: string;
}>) {
    const { __ } = lang();
    const isMounted = useIsMounted();

    setLayoutProps({
        breadcrumbs: [
            {
                title: __('settings_pages.profile_page.head_title'),
                href: edit(),
            },
        ],
    });

    useIsomorphicLayoutEffect(() => {
        if (!isMounted) {
            return;
        }
    }, [isMounted, setLayoutProps]);

    return (
        <Fragment>
            <Head title={__('settings_pages.profile_page.head_title')} />
            <h1 className="sr-only">{__('settings_pages.profile_page.head_title')}</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title={__('settings_pages.profile_page.profile_info.title')}
                    description={__('settings_pages.profile_page.profile_info.description')}
                />

                <BaseAccountUpdateForm mustVerifyEmail={mustVerifyEmail} status={status} />
            </div>

            <Separator className="my-6" />

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title={__('settings_pages.profile_page.app_language.title')}
                    description={__('settings_pages.profile_page.app_language.description')}
                />

                <AppLanguageSelector />
            </div>

            <Separator className="mb-6" />

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title={__('settings_pages.profile_page.personal_profile_manager.title')}
                    description={__('settings_pages.profile_page.personal_profile_manager.description')}
                />

                <PersonalProfileManagerForm />
            </div>

            <DeleteUser />
        </Fragment>
    );
}
