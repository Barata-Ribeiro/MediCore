import { edit } from '@/routes/medical-file';
import { Head } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

// TODO: implement medical file type
export default function MedicalFile({ file }: Readonly<{ file: any }>) {
    return (
        <Fragment>
            <Head title="Medical file settings" />

            <h1 className="sr-only">Medical file settings</h1>

            {/* TODO: implement medical file settings form */}
            <pre>{JSON.stringify(file, null, 2)}</pre>
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
