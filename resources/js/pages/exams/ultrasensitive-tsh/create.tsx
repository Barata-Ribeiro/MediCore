import UltrasensitiveTshForm from '@/components/forms/exams/ultrasensitive-tsh.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/ultrasensitive-tsh';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    setLayoutProps({
        title: 'Create Ultrasensitive TSH record',
        description: 'Register a new Ultrasensitive TSH result for yourself',
        breadcrumbs: [
            { title: 'Ultrasensitive TSH', href: index() },
            { title: 'Create', href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title="Create Ultrasensitive TSH record" />
            <h1 className="sr-only">Create Ultrasensitive TSH record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UltrasensitiveTshForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By creating an Ultrasensitive TSH record, you can track thyroid changes over time and spot
                        trends that may be useful during routine follow-up.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
