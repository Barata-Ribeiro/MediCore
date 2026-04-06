import VitaminD3Form from '@/components/forms/exams/vitamin-d3.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/vitamin-d3';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    setLayoutProps({
        title: 'Create Vitamin D3 record',
        description: 'Register a new Vitamin D3 result for yourself',
        breadcrumbs: [
            { title: 'Vitamin D3', href: index() },
            { title: 'Create', href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title="Create Vitamin D3 record" />
            <h1 className="sr-only">Create Vitamin D3 record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <VitaminD3Form />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By creating a Vitamin D3 record, you can track your vitamin D levels over time and gain insights
                        into your overall health. Regular monitoring of your vitamin D levels can help you make informed
                        decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
