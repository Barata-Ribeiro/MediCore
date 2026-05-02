import UricAcidForm from '@/components/forms/exams/uric-acid.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/uric-acid';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    const { __ } = lang();

    setLayoutProps({
        title: __('uric_acid_pages.create.title'),
        description: __('uric_acid_pages.create.description'),
        breadcrumbs: [
            { title: __('uric_acid_pages.create.breadcrumbs.index'), href: index() },
            { title: __('uric_acid_pages.create.breadcrumbs.current'), href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title={__('uric_acid_pages.create.head_title')} />
            <h1 className="sr-only">{__('uric_acid_pages.create.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('uric_acid_pages.shared.back_label')}
                        aria-label={__('uric_acid_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('uric_acid_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UricAcidForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('uric_acid_pages.create.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
