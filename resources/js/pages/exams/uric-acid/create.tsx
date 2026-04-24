import UricAcidForm from '@/components/forms/exams/uric-acid.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/uric-acid';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    setLayoutProps({
        title: 'Create Uric Acid record',
        description: 'Register a new Uric Acid result for yourself',
        breadcrumbs: [
            { title: 'Uric Acid', href: index() },
            { title: 'Create', href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title="Create Uric Acid record" />
            <h1 className="sr-only">Create Uric Acid record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UricAcidForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By creating a Uric Acid record, you can track your uric acid levels over time and gain insights
                        into your overall health. Regular monitoring can help you make informed decisions about your
                        lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
