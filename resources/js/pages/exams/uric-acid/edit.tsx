import UricAcidForm from '@/components/forms/exams/uric-acid.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/uric-acid';
import type { UricAcid } from '@/types/application/exams/uric-acid';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    uricAcid: UricAcid;
};

export default function Edit({ uricAcid }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('uric_acid_pages.edit.title'),
        description: __('uric_acid_pages.edit.description'),
        breadcrumbs: [
            { title: __('uric_acid_pages.edit.breadcrumbs.index'), href: index() },
            { title: __('uric_acid_pages.edit.breadcrumbs.current'), href: edit(uricAcid.id) },
        ],
    });

    return (
        <Fragment>
            <Head title={__('uric_acid_pages.edit.head_title')} />
            <h1 className="sr-only">{__('uric_acid_pages.edit.head_title')}</h1>

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
                    <UricAcidForm uricAcid={uricAcid} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('uric_acid_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
