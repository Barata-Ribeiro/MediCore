import Heading from '@/components/common/heading';
import AppearanceTabs from '@/components/helpers/appearance-tabs';
import { edit as editAppearance } from '@/routes/appearance';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function Appearance() {
    const { __ } = lang();

    setLayoutProps({
        breadcrumbs: [
            {
                title: __('settings_pages.appearance_page.title'),
                href: editAppearance(),
            },
        ],
    });

    return (
        <Fragment>
            <Head title={__('settings_pages.appearance_page.head_title')} />

            <h1 className="sr-only">{__('settings_pages.appearance_page.title')}</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title={__('settings_pages.appearance_page.title')}
                    description={__('settings_pages.appearance_page.description')}
                />
                <AppearanceTabs />
            </div>
        </Fragment>
    );
}
