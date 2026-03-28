import MedicalFileManagerForm from '@/components/forms/settings/medical-file-manager.form';
import { edit } from '@/routes/medical-file';
import { Head } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function MedicalFile() {
    return (
        <Fragment>
            <Head title="Medical file settings" />

            <h1 className="sr-only">Medical file settings</h1>

            <MedicalFileManagerForm />
        </Fragment>
    );
}

MedicalFile.layout = {
    breadcrumbs: [
        {
            title: 'Medical file settings',
            href: edit(),
        },
    ],
};
