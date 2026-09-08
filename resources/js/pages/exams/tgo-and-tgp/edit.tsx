import TgoAndTgpForm from '@/components/forms/exams/tgo-and-tgp.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/tgo-and-tgp';
import type { TgoAndTgp } from '@/types/application/exams/tgo-and-tgp';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    tgoAndTgp: TgoAndTgp;
};

export default function Edit({ tgoAndTgp }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('tgo_and_tgp_pages.edit.title'),
        description: __('tgo_and_tgp_pages.edit.description'),
        breadcrumbs: [
            { title: __('tgo_and_tgp_pages.edit.breadcrumbs.index'), href: index() },
            { title: __('tgo_and_tgp_pages.edit.breadcrumbs.current'), href: edit(tgoAndTgp.id) },
        ],
    });

    return (
        <Fragment>
            <Head title={__('tgo_and_tgp_pages.edit.head_title')} />
            <h1 className="sr-only">{__('tgo_and_tgp_pages.edit.head_title')}</h1>

            <Card className="mx-auto w-full flex-col gap-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('tgo_and_tgp_pages.shared.back_label')}
                        aria-label={__('tgo_and_tgp_pages.shared.back_label')}
                        render={
                            <Link href={index()} as="button" prefetch="hover">
                                <ArrowLeftIcon aria-hidden data-icon="inline-start" />{' '}
                                {__('tgo_and_tgp_pages.shared.back')}
                            </Link>
                        }
                    />
                </CardHeader>
                <CardContent>
                    <TgoAndTgpForm tgoAndTgp={tgoAndTgp} />
                </CardContent>
                <CardFooter>
                    <p className="text-muted-foreground text-sm">{__('tgo_and_tgp_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
