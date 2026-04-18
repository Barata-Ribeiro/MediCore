import UltrasensitiveTshForm from '@/components/forms/exams/ultrasensitive-tsh.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/ultrasensitive-tsh';
import type { UltrasensitiveTsh } from '@/types/application/exams/ultrasensitive-tsh';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    ultrasensitiveTsh: UltrasensitiveTsh;
};

export default function Edit({ ultrasensitiveTsh }: Readonly<Props>) {
    setLayoutProps({
        title: 'Edit Ultrasensitive TSH record',
        description: 'Update an existing Ultrasensitive TSH result',
        breadcrumbs: [
            { title: 'Ultrasensitive TSH', href: index() },
            { title: 'Edit', href: edit(ultrasensitiveTsh.id) },
        ],
    });

    return (
        <Fragment>
            <Head title="Edit Ultrasensitive TSH record" />
            <h1 className="sr-only">Edit Ultrasensitive TSH record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UltrasensitiveTshForm ultrasensitiveTsh={ultrasensitiveTsh} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By editing an Ultrasensitive TSH record, you can keep your thyroid history accurate and easier
                        to review over time.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
