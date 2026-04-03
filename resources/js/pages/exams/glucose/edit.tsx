import GlucoseForm from '@/components/forms/exams/glucose.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/glucose';
import type { Glucose } from '@/types/application/exams/glucose';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    glucose: Glucose;
};

export default function Edit({ glucose }: Readonly<Props>) {
    setLayoutProps({
        title: 'Edit Glucose Result',
        description: 'Update your glucose result to keep your health records accurate and up-to-date',
        breadcrumbs: [
            {
                title: 'Glucose',
                href: index(),
            },
            {
                title: 'Edit',
                href: edit(glucose.id),
            },
        ],
    });

    return (
        <Fragment>
            <Head title="Edit Glucose Result" />
            <h1 className="sr-only">Edit Glucose Result</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <GlucoseForm glucose={glucose} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By editing a glucose result, you can track your blood sugar levels over time and gain insights
                        into your overall health. Regular monitoring of your glucose levels can help you make informed
                        decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
