// Components
import TextLink from '@/components/helpers/text-link';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';
import { send } from '@/routes/verification';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form, Head, setLayoutProps } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

export default function VerifyEmail({ status }: Readonly<{ status?: string }>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('auth_pages.verify_email_page.title'),
        description: __('auth_pages.verify_email_page.description'),
    });

    return (
        <Fragment>
            <Head title={__('auth_pages.verify_email_page.head_title')} />

            <Activity mode={status === 'verification-link-sent' ? 'visible' : 'hidden'}>
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    {__('auth_pages.verify_email_page.resend_message')}
                </div>
            </Activity>

            <Form {...send.form()} className="space-y-6 text-center">
                {({ processing }) => (
                    <Fragment>
                        <Button disabled={processing} variant="secondary">
                            <Activity mode={processing ? 'visible' : 'hidden'}>
                                <Spinner aria-hidden />
                            </Activity>
                            {__('auth_pages.verify_email_page.form.submit')}
                        </Button>

                        <TextLink href={logout()} className="mx-auto block text-sm">
                            {__('auth_pages.verify_email_page.form.logout')}
                        </TextLink>
                    </Fragment>
                )}
            </Form>
        </Fragment>
    );
}
