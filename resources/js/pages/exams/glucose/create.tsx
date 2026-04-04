import GlucoseForm from '@/components/forms/exams/glucose.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/glucose';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    setLayoutProps({
        title: 'Create Glucose Result',
        description: 'Register a new glucose result for yourself',
        breadcrumbs: [
            { title: 'Glucose', href: index() },
            { title: 'Create', href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title="Create Glucose Result" />
            <h1 className="sr-only">Create Glucose Result</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <GlucoseForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By creating a glucose result, you can track your blood sugar levels over time and gain insights
                        into your overall health. Regular monitoring of your glucose levels can help you make informed
                        decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
