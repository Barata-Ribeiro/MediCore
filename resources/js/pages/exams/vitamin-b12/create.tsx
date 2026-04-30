import VitaminB12Form from '@/components/forms/exams/vitamin-b12.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { create, index } from '@/routes/vitamin-b12';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

export default function Create() {
    const { __ } = lang();

    setLayoutProps({
        title: __('vitamin_b12_pages.create.title'),
        description: __('vitamin_b12_pages.create.description'),
        breadcrumbs: [
            { title: __('vitamin_b12_pages.create.breadcrumbs.index'), href: index() },
            { title: __('vitamin_b12_pages.create.breadcrumbs.current'), href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title={__('vitamin_b12_pages.create.head_title')} />
            <h1 className="sr-only">{__('vitamin_b12_pages.create.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('vitamin_b12_pages.shared.back_label')}
                        aria-label={__('vitamin_b12_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('vitamin_b12_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <VitaminB12Form />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('vitamin_b12_pages.create.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
