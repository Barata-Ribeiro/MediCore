import UreaAndCreatinineForm from '@/components/forms/exams/urea-and-creatinine.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/urea-and-creatinine';
import type { UreaAndCreatinine } from '@/types/application/exams/urea-and-creatinine';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    ureaAndCreatinine: UreaAndCreatinine;
};

export default function Edit({ ureaAndCreatinine }: Readonly<Props>) {
    setLayoutProps({
        title: 'Edit Urea and Creatinine record',
        description: 'Update an existing Urea and Creatinine result',
        breadcrumbs: [
            { title: 'Urea and Creatinine', href: index() },
            { title: 'Edit', href: edit(ureaAndCreatinine.id) },
        ],
    });

    return (
        <Fragment>
            <Head title="Edit Urea and Creatinine record" />
            <h1 className="sr-only">Edit Urea and Creatinine record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UreaAndCreatinineForm ureaAndCreatinine={ureaAndCreatinine} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By editing a Urea and Creatinine record, you can update your kidney function over time and gain
                        insights into your overall health. Regular monitoring of your kidney function can help you make
                        informed decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
