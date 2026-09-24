import TotalProteinsAndFractionsForm from '@/components/forms/exams/total-protein-and-fractions.form';
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
        title: __('total_proteins_and_fractions_pages.create.title'),
        description: __('total_proteins_and_fractions_pages.create.description'),
        breadcrumbs: [
            { title: __('total_proteins_and_fractions_pages.create.breadcrumbs.index'), href: index() },
            { title: __('total_proteins_and_fractions_pages.create.breadcrumbs.current'), href: create() },
        ],
    });

    return (
        <Fragment>
            <Head title={__('total_proteins_and_fractions_pages.create.head_title')} />
            <h1 className="sr-only">{__('total_proteins_and_fractions_pages.create.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('total_proteins_and_fractions_pages.shared.back_label')}
                        aria-label={__('total_proteins_and_fractions_pages.shared.back_label')}
                        render={
                            <Link href={index()} as="button" prefetch="hover">
                                <ArrowLeftIcon aria-hidden size={14} />{' '}
                                {__('total_proteins_and_fractions_pages.shared.back')}
                            </Link>
                        }
                    />
                </CardHeader>
                <CardContent>
                    <TotalProteinsAndFractionsForm />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">
                        {__('total_proteins_and_fractions_pages.create.footer')}
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
