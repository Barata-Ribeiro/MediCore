import VitaminB12Form from '@/components/forms/exams/vitamin-b12.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/vitamin-b12';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    setLayoutProps({
        title: 'Create Vitamin B12 record',
        description: 'Register a new Vitamin B12 result for yourself',
        breadcrumbs: [
            { title: 'Vitamin B12', href: index() },
            { title: 'Create', href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title="Create Vitamin B12 record" />
            <h1 className="sr-only">Create Vitamin B12 record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <VitaminB12Form />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By creating a Vitamin B12 record, you can track your vitamin B12 levels over time and gain
                        insights into your overall health. Regular monitoring of your vitamin B12 levels can help you
                        make informed decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
