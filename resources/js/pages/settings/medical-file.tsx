import Heading from '@/components/common/heading';
import MedicalFileManagerForm from '@/components/forms/settings/medical-file-manager.form';
import { edit } from '@/routes/medical-file';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function MedicalFile() {
    const { __ } = lang();

    setLayoutProps({
        breadcrumbs: [
            {
                title: __('settings_pages.medical_file_page.title'),
                href: edit(),
            },
        ],
    });

    return (
        <Fragment>
            <Head title={__('settings_pages.medical_file_page.head_title')} />

            <h1 className="sr-only">{__('settings_pages.medical_file_page.title')}</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title={__('settings_pages.medical_file_page.title')}
                    description={__('settings_pages.medical_file_page.description')}
                />

                <MedicalFileManagerForm />
            </div>
        </Fragment>
    );
}
