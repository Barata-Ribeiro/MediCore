import CbcCountForm from '@/components/forms/exams/cbc-count.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/complete-blood-count';
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    completeBloodCount: CompleteBloodCount;
};

export default function Edit({ completeBloodCount }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('complete_blood_count_pages.edit.title'),
        description: __('complete_blood_count_pages.edit.description'),
        breadcrumbs: [
            {
                title: __('complete_blood_count_pages.edit.breadcrumbs.index'),
                href: index(),
            },
            {
                title: __('complete_blood_count_pages.edit.breadcrumbs.current'),
                href: edit(completeBloodCount.id),
            },
        ],
    });

    return (
        <Fragment>
            <Head title={__('complete_blood_count_pages.edit.head_title')} />
            <h1 className="sr-only">{__('complete_blood_count_pages.edit.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('complete_blood_count_pages.shared.back_label')}
                        aria-label={__('complete_blood_count_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('complete_blood_count_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <CbcCountForm cbcCount={completeBloodCount} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('complete_blood_count_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
