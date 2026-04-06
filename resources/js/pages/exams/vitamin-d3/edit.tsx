import VitaminD3Form from '@/components/forms/exams/vitamin-d3.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/vitamin-d3';
import type { VitaminD3 } from '@/types/application/exams/vitamin-d3';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    vitaminD3: VitaminD3;
};

export default function Edit({ vitaminD3 }: Readonly<Props>) {
    setLayoutProps({
        title: 'Edit Vitamin D3 record',
        description: 'Update an existing Vitamin D3 result',
        breadcrumbs: [
            { title: 'Vitamin D3', href: index() },
            { title: 'Edit', href: edit(vitaminD3.id) },
        ],
    });

    return (
        <Fragment>
            <Head title="Edit Vitamin D3 record" />
            <h1 className="sr-only">Edit Vitamin D3 record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <VitaminD3Form vitaminD3={vitaminD3} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By editing a Vitamin D3 record, you can update your vitamin D levels over time and gain insights
                        into your overall health. Regular monitoring of your vitamin D levels can help you make informed
                        decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
