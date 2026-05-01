import GlucoseForm from '@/components/forms/exams/glucose.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/glucose';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    const { __ } = lang();

    setLayoutProps({
        title: __('glucose_pages.create.title'),
        description: __('glucose_pages.create.description'),
        breadcrumbs: [
            { title: __('glucose_pages.create.breadcrumbs.index'), href: index() },
            { title: __('glucose_pages.create.breadcrumbs.current'), href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title={__('glucose_pages.create.head_title')} />
            <h1 className="sr-only">{__('glucose_pages.create.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('glucose_pages.shared.back_label')}
                        aria-label={__('glucose_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('glucose_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <GlucoseForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('glucose_pages.create.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
