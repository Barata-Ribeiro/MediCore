import LipidProfileForm from '@/components/forms/exams/lipid-profile.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/lipid-profile';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
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
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
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
