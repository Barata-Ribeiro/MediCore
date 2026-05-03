import UreaAndCreatinineForm from '@/components/forms/exams/urea-and-creatinine.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/urea-and-creatinine';
import type { UreaAndCreatinine } from '@/types/application/exams/urea-and-creatinine';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    ureaAndCreatinine: UreaAndCreatinine;
};

export default function Edit({ ureaAndCreatinine }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('urea_and_creatinine_pages.edit.title'),
        description: __('urea_and_creatinine_pages.edit.description'),
        breadcrumbs: [
            { title: __('urea_and_creatinine_pages.edit.breadcrumbs.index'), href: index() },
            { title: __('urea_and_creatinine_pages.edit.breadcrumbs.current'), href: edit(ureaAndCreatinine.id) },
        ],
    });

    return (
        <Fragment>
            <Head title={__('urea_and_creatinine_pages.edit.head_title')} />
            <h1 className="sr-only">{__('urea_and_creatinine_pages.edit.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('urea_and_creatinine_pages.shared.back_label')}
                        aria-label={__('urea_and_creatinine_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('urea_and_creatinine_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UreaAndCreatinineForm ureaAndCreatinine={ureaAndCreatinine} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('urea_and_creatinine_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
