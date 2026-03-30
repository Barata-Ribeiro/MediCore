import LipidProfileForm from '@/components/forms/exams/lipid-profile.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/lipid-profile';
import type { LipidProfile } from '@/types/application/exams/lipid-profile';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    lipidProfile: LipidProfile;
};

export default function Edit({ lipidProfile }: Readonly<Props>) {
    setLayoutProps({
        title: 'Edit Lipid Profile',
        description: 'Update your lipid profile result to keep your health records accurate and up-to-date',
        breadcrumbs: [
            {
                title: 'Lipid Profiles',
                href: index(),
            },
            {
                title: 'Edit',
                href: edit(lipidProfile.id),
            },
        ],
    });

    return (
        <Fragment>
            <Head title="Edit Lipid Profile" />
            <h1 className="sr-only">Edit Lipid Profile</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <LipidProfileForm lipidProfile={lipidProfile} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By editing a lipid profile, you can track your cholesterol levels over time and gain insights
                        into your cardiovascular health. Regular monitoring of your lipid profile can help you make
                        informed decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
