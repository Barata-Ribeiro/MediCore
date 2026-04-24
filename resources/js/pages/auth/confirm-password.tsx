import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/password/confirm';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form, Head, setLayoutProps } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

export default function ConfirmPassword() {
    const { __ } = lang();

    setLayoutProps({
        title: __('auth_pages.confirm_password_page.title'),
        description: __('auth_pages.confirm_password_page.description'),
    });

    return (
        <Fragment>
            <Head title={__('auth_pages.confirm_password_page.head_title')} />

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                disableWhileProcessing
                className="inert:pointer-events-none inert:grayscale-100"
            >
                {({ processing, errors }) => (
                    <div className="space-y-6">
                        <Field data-invalid={!!errors['password']}>
                            <FieldLabel htmlFor="password">
                                {__('auth_pages.confirm_password_page.form.password')}
                            </FieldLabel>
                            <PasswordInput
                                id="password"
                                name="password"
                                placeholder={__('auth_pages.confirm_password_page.form.password')}
                                autoComplete="current-password"
                                autoFocus
                                aria-invalid={!!errors['password']}
                            />

                            <InputError message={errors['password']} />
                        </Field>

                        <div className="flex items-center">
                            <Button className="w-full" data-test="confirm-password-button">
                                <Activity mode={processing ? 'visible' : 'hidden'}>
                                    <Spinner />{' '}
                                </Activity>
                                {__('auth_pages.confirm_password_page.form.submit')}
                            </Button>
                        </div>
                    </div>
                )}
            </Form>
        </Fragment>
    );
}
