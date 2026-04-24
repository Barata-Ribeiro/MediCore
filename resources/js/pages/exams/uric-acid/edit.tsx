import UricAcidForm from '@/components/forms/exams/uric-acid.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/uric-acid';
import type { UricAcid } from '@/types/application/exams/uric-acid';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    uricAcid: UricAcid;
};

export default function Edit({ uricAcid }: Readonly<Props>) {
    setLayoutProps({
        title: 'Edit Uric Acid record',
        description: 'Update an existing Uric Acid result',
        breadcrumbs: [
            { title: 'Uric Acid', href: index() },
            { title: 'Edit', href: edit(uricAcid.id) },
        ],
    });

    return (
        <Fragment>
            <Head title="Edit Uric Acid record" />
            <h1 className="sr-only">Edit Uric Acid record</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button variant="outline" size="sm" className="w-fit" title="Go Back" aria-label="Go Back" asChild>
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> Back
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UricAcidForm uricAcid={uricAcid} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        By editing a Uric Acid record, you can keep your uric acid history accurate and easier to review
                        over time.
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
