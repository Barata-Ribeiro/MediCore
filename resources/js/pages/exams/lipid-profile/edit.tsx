import LipidProfileForm from '@/components/forms/exams/lipid-profile.form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { edit, index } from '@/routes/lipid-profile';
import type { LipidProfile } from '@/types/application/exams/lipid-profile';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ArrowLeftIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    lipidProfile: LipidProfile;
};

export default function Edit({ lipidProfile }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('lipid_profile_pages.edit.title'),
        description: __('lipid_profile_pages.edit.description'),
        breadcrumbs: [
            {
                title: __('lipid_profile_pages.edit.breadcrumbs.index'),
                href: index(),
            },
            {
                title: __('lipid_profile_pages.edit.breadcrumbs.current'),
                href: edit(lipidProfile.id),
            },
        ],
    });

    return (
        <Fragment>
            <Head title={__('lipid_profile_pages.edit.head_title')} />
            <h1 className="sr-only">{__('lipid_profile_pages.edit.head_title')}</h1>

            <Card className="mx-auto w-full flex-col space-y-4">
                <CardHeader>
                    <Button
                        variant="outline"
                        size="sm"
                        className="w-fit"
                        title={__('lipid_profile_pages.shared.back_label')}
                        aria-label={__('lipid_profile_pages.shared.back_label')}
                        asChild
                    >
                        <Link href={index()} as="button" prefetch="hover">
                            <ArrowLeftIcon aria-hidden size={14} /> {__('lipid_profile_pages.shared.back')}
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <LipidProfileForm lipidProfile={lipidProfile} />
                </CardContent>
                <CardFooter>
                    <p className="text-sm text-muted-foreground">{__('lipid_profile_pages.edit.footer')}</p>
                </CardFooter>
            </Card>
        </Fragment>
    );
}
