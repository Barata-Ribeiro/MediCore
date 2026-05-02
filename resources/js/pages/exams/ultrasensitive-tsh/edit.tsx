import UltrasensitiveTshForm from '@/components/forms/exams/ultrasensitive-tsh.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/ultrasensitive-tsh';
import type { UltrasensitiveTsh } from '@/types/application/exams/ultrasensitive-tsh';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    ultrasensitiveTsh: UltrasensitiveTsh;
};

export default function Edit({ ultrasensitiveTsh }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('ultrasensitive_tsh_pages.edit.title'),
        description: __('ultrasensitive_tsh_pages.edit.description'),
        breadcrumbs: [
            { title: __('ultrasensitive_tsh_pages.edit.breadcrumbs.index'), href: index() },
            { title: __('ultrasensitive_tsh_pages.edit.breadcrumbs.current'), href: edit(ultrasensitiveTsh.id) },
        ],
    });

    return (
        <Fragment>
            <Head title={__('ultrasensitive_tsh_pages.edit.head_title')} />
            <h1 className="sr-only">{__('ultrasensitive_tsh_pages.edit.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('ultrasensitive_tsh_pages.shared.back_label')}
                        aria-label={__('ultrasensitive_tsh_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('ultrasensitive_tsh_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <UltrasensitiveTshForm ultrasensitiveTsh={ultrasensitiveTsh} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('ultrasensitive_tsh_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
