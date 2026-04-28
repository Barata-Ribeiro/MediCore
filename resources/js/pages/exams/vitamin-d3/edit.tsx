import VitaminD3Form from '@/components/forms/exams/vitamin-d3.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/vitamin-d3';
import type { VitaminD3 } from '@/types/application/exams/vitamin-d3';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    vitaminD3: VitaminD3;
};

export default function Edit({ vitaminD3 }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('vitamin_d3_pages.edit.title'),
        description: __('vitamin_d3_pages.edit.description'),
        breadcrumbs: [
            { title: __('vitamin_d3_pages.edit.breadcrumbs.index'), href: index() },
            { title: __('vitamin_d3_pages.edit.breadcrumbs.current'), href: edit(vitaminD3.id) },
        ],
    });

    return (
        <Fragment>
            <Head title={__('vitamin_d3_pages.edit.head_title')} />
            <h1 className="sr-only">{__('vitamin_d3_pages.edit.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('vitamin_d3_pages.shared.back_label')}
                        aria-label={__('vitamin_d3_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('vitamin_d3_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <VitaminD3Form vitaminD3={vitaminD3} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('vitamin_d3_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
