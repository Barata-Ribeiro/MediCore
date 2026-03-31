import CbcCountForm from '@/components/forms/exams/cbc-count.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/complete-blood-count';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function CreateCompleteBloodCount() {
    setLayoutProps({
        title: 'Create Complete Blood Count',
        description: 'Register a new complete blood count result for yourself',
        breadcrumbs: [
            { title: 'Complete Blood Count', href: index() },
            { title: 'Create', href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title="Create Complete Blood Count" />
            <h1 className="sr-only">Create Complete Blood Count</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <CbcCountForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By creating a complete blood count, you can track your blood health over time and gain insights
                        into your overall well-being. Regular monitoring of your complete blood count can help you make
                        informed decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
