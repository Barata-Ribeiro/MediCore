import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import TextLink from '@/components/helpers/text-link';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form, Head, setLayoutProps } from '@inertiajs/react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';

export default function Register() {
    const { __ } = lang();

    setLayoutProps({
        title: __('auth_pages.register_page.title'),
        description: __('auth_pages.register_page.description'),
    });

    return (
        <Fragment>
            <Head title={__('auth_pages.register_page.head_title')} />
            <Form
                {...store.form()}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="flex flex-col gap-6 inert:pointer-events-none inert:grayscale-100"
            >
                {({ processing, errors }) => (
                    <Fragment>
                        <div className="grid gap-6">
                            <Field data-invalid={!!errors['name']}>
                                <FieldLabel htmlFor="name">{__('auth_pages.register_page.form.name')}</FieldLabel>
                                <Input
                                    id="name"
                                    type="text"
                                    required
                                    autoFocus
                                    tabIndex={0}
                                    autoComplete="name"
                                    name="name"
                                    placeholder={__('auth_pages.register_page.form.name')}
                                    aria-invalid={!!errors['name']}
                                />
                                <InputError message={errors['name']} className="mt-2" />
                            </Field>

                            <Field data-invalid={!!errors['email']}>
                                <FieldLabel htmlFor="email">{__('auth_pages.register_page.form.email')}</FieldLabel>
                                <Input
                                    id="email"
                                    type="email"
                                    required
                                    tabIndex={0}
                                    autoComplete="email"
                                    name="email"
                                    placeholder="email@example.com"
                                    aria-invalid={!!errors['email']}
                                />
                                <InputError message={errors['email']} />
                            </Field>

                            <Field data-invalid={!!errors['password']}>
                                <FieldLabel htmlFor="password">
                                    {__('auth_pages.register_page.form.password')}
                                </FieldLabel>
                                <PasswordInput
                                    id="password"
                                    required
                                    tabIndex={0}
                                    autoComplete="new-password"
                                    name="password"
                                    placeholder={__('auth_pages.register_page.form.password')}
                                    aria-invalid={!!errors['password']}
                                />
                                <InputError message={errors['password']} />
                            </Field>

                            <Field data-invalid={!!errors['password_confirmation']}>
                                <FieldLabel htmlFor="password_confirmation">
                                    {__('auth_pages.register_page.form.password_confirmation')}
                                </FieldLabel>
                                <PasswordInput
                                    id="password_confirmation"
                                    required
                                    tabIndex={0}
                                    autoComplete="new-password"
                                    name="password_confirmation"
                                    placeholder={__('auth_pages.register_page.form.password_confirmation')}
                                    aria-invalid={!!errors['password_confirmation']}
                                />
                                <InputError message={errors['password_confirmation']} />
                            </Field>

                            <Button type="submit" className="mt-2 w-full" tabIndex={0} data-test="register-user-button">
                                <Activity mode={processing ? 'visible' : 'hidden'}>
                                    <Spinner aria-hidden />
                                </Activity>
                                {__('auth_pages.register_page.form.submit')}
                            </Button>
                        </div>

                        <div className="text-center text-sm text-muted-foreground">
                            {__('auth_pages.register_page.form.login_message_text')}{' '}
                            <TextLink href={login()} tabIndex={0}>
                                {__('auth_pages.register_page.form.login_message_link')}
                            </TextLink>
                        </div>
                    </Fragment>
                )}
            </Form>
        </Fragment>
    );
}
