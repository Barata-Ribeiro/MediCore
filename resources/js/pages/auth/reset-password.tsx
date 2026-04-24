import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { update } from '@/routes/password';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form, Head, setLayoutProps } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

type Props = {
    token: string;
    email: string;
};

export default function ResetPassword({ token, email }: Readonly<Props>) {
    const { __ } = lang();

    setLayoutProps({
        title: __('auth_pages.reset_password_page.title'),
        description: __('auth_pages.reset_password_page.description'),
    });

    return (
        <Fragment>
            <Head title={__('auth_pages.reset_password_page.head_title')} />

            <Form
                {...update.form()}
                transform={(data) => ({ ...data, token, email })}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="inert:pointer-events-none inert:grayscale-100"
            >
                {({ processing, errors }) => (
                    <div className="grid gap-6">
                        <Field data-invalid={!!errors['email']}>
                            <FieldLabel htmlFor="email">{__('auth_pages.reset_password_page.form.email')}</FieldLabel>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                autoComplete="email"
                                value={email}
                                className="mt-1 block w-full"
                                aria-invalid={!!errors['email']}
                                readOnly
                            />
                            <InputError message={errors['email']} className="mt-2" />
                        </Field>

                        <Field data-invalid={!!errors['password']}>
                            <FieldLabel htmlFor="password">
                                {__('auth_pages.reset_password_page.form.password')}
                            </FieldLabel>
                            <PasswordInput
                                id="password"
                                name="password"
                                autoComplete="new-password"
                                className="mt-1 block w-full"
                                placeholder={__('auth_pages.reset_password_page.form.password_placeholder')}
                                autoFocus
                                aria-invalid={!!errors['password']}
                            />
                            <InputError message={errors['password']} />
                        </Field>

                        <Field data-invalid={!!errors['password_confirmation']}>
                            <FieldLabel htmlFor="password_confirmation">
                                {__('auth_pages.reset_password_page.form.password_confirmation')}
                            </FieldLabel>
                            <PasswordInput
                                id="password_confirmation"
                                name="password_confirmation"
                                autoComplete="new-password"
                                className="mt-1 block w-full"
                                placeholder={__(
                                    'auth_pages.reset_password_page.form.password_confirmation_placeholder',
                                )}
                                aria-invalid={!!errors['password_confirmation']}
                            />
                            <InputError message={errors['password_confirmation']} className="mt-2" />
                        </Field>

                        <Button
                            type="submit"
                            className="mt-4 w-full"
                            disabled={processing}
                            data-test="reset-password-button"
                        >
                            <Activity mode={processing ? 'visible' : 'hidden'}>
                                <Spinner aria-hidden />
                            </Activity>
                            {__('auth_pages.reset_password_page.form.submit')}
                        </Button>
                    </div>
                )}
            </Form>
        </Fragment>
    );
}
