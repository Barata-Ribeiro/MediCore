import LipidProfileForm from '@/components/forms/exams/lipid-profile.form';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { create, index } from '@/routes/lipid-profile';
import { Head, setLayoutProps } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    setLayoutProps({
        title: 'Create Lipid Profile',
        description: 'Register a new lipid profile result for yourself',
    });

    return (
        <Fragment>
            <Head title="Create Lipid Profile" />
            <h1 className="sr-only">Create Lipid Profile</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardContent>
                    <LipidProfileForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By creating a lipid profile, you can track your cholesterol levels over time and and gain
                        insights into your cardiovascular health. Regular monitoring of your lipid profile can help you
                        make informed decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}

Create.layout = {
    breadcrumbs: [
        {
            title: 'Lipid Profiles',
            href: index(),
        },
        {
            title: 'Create',
            href: create(),
        },
    ],
};
