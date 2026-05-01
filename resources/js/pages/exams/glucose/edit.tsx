import GlucoseForm from '@/components/forms/exams/glucose.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/glucose';
import type { Glucose } from '@/types/application/exams/glucose';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    glucose: Glucose;
};

export default function Edit({ glucose }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('glucose_pages.edit.title'),
        description: __('glucose_pages.edit.description'),
        breadcrumbs: [
            {
                title: __('glucose_pages.edit.breadcrumbs.index'),
                href: index(),
            },
            {
                title: __('glucose_pages.edit.breadcrumbs.current'),
                href: edit(glucose.id),
            },
        ],
    });

    return (
        <Fragment>
            <Head title={__('glucose_pages.edit.head_title')} />
            <h1 className="sr-only">{__('glucose_pages.edit.head_title')}</h1>

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
                    <GlucoseForm glucose={glucose} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('glucose_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
