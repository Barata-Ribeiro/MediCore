import InputError from '@/components/helpers/input-error';
import TextLink from '@/components/helpers/text-link';
import { Button } from '@/components/ui/button';
import { Field } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { email } from '@/routes/password';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form, Head, setLayoutProps } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';

export default function ForgotPassword({ status }: Readonly<{ status?: string }>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('auth_pages.forgot_password_page.title'),
        description: __('auth_pages.forgot_password_page.description'),
    });

    return (
        <Fragment>
            <Head title={__('auth_pages.forgot_password_page.head_title')} />

            {status && <div className="mb-4 text-center text-sm font-medium text-green-600">{status}</div>}

            <div className="space-y-6">
                <Form
                    {...email.form()}
                    disableWhileProcessing
                    className="inert:pointer-events-none inert:grayscale-100"
                >
                    {({ processing, errors }) => (
                        <Fragment>
                            <Field data-invalid={!!errors['email']}>
                                <Label htmlFor="email">{__('auth_pages.forgot_password_page.form.email')}</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    autoComplete="off"
                                    autoFocus
                                    placeholder="email@example.com"
                                    aria-invalid={!!errors['email']}
                                />

                                <InputError message={errors['email']} />
                            </Field>

                            <div className="my-6 flex items-center justify-start">
                                <Button className="w-full" data-test="email-password-reset-link-button">
                                    <Activity mode={processing ? 'visible' : 'hidden'}>
                                        <LoaderCircle aria-hidden className="size-4 animate-spin" />
                                    </Activity>
                                    {__('auth_pages.forgot_password_page.form.submit')}
                                </Button>
                            </div>
                        </Fragment>
                    )}
                </Form>

                <div className="space-x-1 text-center text-sm text-muted-foreground">
                    <span>{__('auth_pages.forgot_password_page.return_message_text')}</span>
                    <TextLink href={login()}>{__('auth_pages.forgot_password_page.return_message_link')}</TextLink>
                </div>
            </div>
        </Fragment>
    );
}
