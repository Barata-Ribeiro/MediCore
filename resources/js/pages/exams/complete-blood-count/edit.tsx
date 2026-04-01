import CbcCountForm from '@/components/forms/exams/cbc-count.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/complete-blood-count';
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    completeBloodCount: CompleteBloodCount;
};

export default function Edit({ completeBloodCount }: Readonly<Props>) {
    setLayoutProps({
        title: 'Edit Complete Blood Count',
        description: 'Update your complete blood count result to keep your health records accurate and up-to-date',
        breadcrumbs: [
            {
                title: 'Complete Blood Counts',
                href: index(),
            },
            {
                title: 'Edit',
                href: edit(completeBloodCount.id),
            },
        ],
    });

    return (
        <Fragment>
            <Head title="Edit Complete Blood Count" />
            <h1 className="sr-only">Edit Complete Blood Count</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <CbcCountForm cbcCount={completeBloodCount} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By updating your complete blood count, you can track your blood health over time and gain
                        insights into your overall well-being. Regular monitoring of your complete blood count can help
                        you make informed decisions about your lifestyle and healthcare.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
