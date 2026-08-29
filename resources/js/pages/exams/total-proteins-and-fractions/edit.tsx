import TotalProteinsAndFractionsForm from '@/components/forms/exams/total-protein-and-fractions.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/lipid-profile';
import type { TotalProteinsAndFractions } from '@/types/application/exams/total-proteins-and-fractions';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    totalProteinsAndFractions: TotalProteinsAndFractions;
};

export default function Edit({ totalProteinsAndFractions }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('total_proteins_and_fractions_pages.edit.title'),
        description: __('total_proteins_and_fractions_pages.edit.description'),
        breadcrumbs: [
            {
                title: __('total_proteins_and_fractions_pages.edit.breadcrumbs.index'),
                href: index(),
            },
            {
                title: __('total_proteins_and_fractions_pages.edit.breadcrumbs.current'),
                href: edit(totalProteinsAndFractions.id),
            },
        ],
    });

    return (
        <Fragment>
            <Head title={__('total_proteins_and_fractions_pages.edit.head_title')} />
            <h1 className="sr-only">{__('total_proteins_and_fractions_pages.edit.head_title')}</h1>

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
                    <TotalProteinsAndFractionsForm totalProteinAndFractions={totalProteinsAndFractions} />
                </CardContent>
                <CardFooter>
                    <p className="text-muted-foreground text-sm">
                        {__('total_proteins_and_fractions_pages.edit.footer')}
                    </p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
