import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import TextLink from '@/components/helpers/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldLabel, FieldSeparator } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import social from '@/routes/social';
import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
};

export default function Login({ status, canResetPassword, canRegister }: Readonly<Props>) {
    setLayoutProps({
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    });

    return (
        <Fragment>
            <Head title="Log in" />

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                disableWhileProcessing
                className="flex flex-col gap-6 inert:pointer-events-none inert:grayscale-100"
            >
                {({ processing, errors }) => (
                    <Fragment>
                        <div className="grid gap-6">
                            <Field data-invalid={!!errors['email']}>
                                <FieldLabel htmlFor="email">Email address</FieldLabel>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autoFocus
                                    tabIndex={0}
                                    autoComplete="email"
                                    placeholder="email@example.com"
                                    aria-invalid={!!errors['email']}
                                />
                                <InputError message={errors['email']} />
                            </Field>

                            <Field data-invalid={!!errors['password']}>
                                <div className="flex items-center">
                                    <FieldLabel htmlFor="password">Password</FieldLabel>
                                    <Activity mode={canResetPassword ? 'visible' : 'hidden'}>
                                        <TextLink href={request()} className="ml-auto text-sm" tabIndex={0}>
                                            Forgot password?
                                        </TextLink>
                                    </Activity>
                                </div>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    required
                                    tabIndex={0}
                                    autoComplete="current-password"
                                    placeholder="Password"
                                    aria-invalid={!!errors['password']}
                                />
                                <InputError message={errors['password']} />
                            </Field>

                            <div className="flex items-center space-x-3">
                                <Checkbox id="remember" name="remember" tabIndex={0} />
                                <Label htmlFor="remember">Remember me</Label>
                            </div>

                            <Button type="submit" className="mt-4 w-full" tabIndex={0} data-test="login-button">
                                <Activity mode={processing ? 'visible' : 'hidden'}>
                                    <Spinner aria-hidden />
                                </Activity>
                                Log in
                            </Button>
                        </div>

                        <Activity mode={canRegister ? 'visible' : 'hidden'}>
                            <div className="text-center text-sm text-muted-foreground">
                                Don't have an account?{' '}
                                <TextLink href={register()} tabIndex={0}>
                                    Sign up
                                </TextLink>
                            </div>
                        </Activity>

                        <FieldSeparator>Or</FieldSeparator>

                        <Button type="button" variant="outline" asChild className="w-full">
                            <Link href={social.redirect('google')} tabIndex={0} as="button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path
                                        d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"
                                        fill="currentColor"
                                    />
                                </svg>
                                Continue with Google
                            </Link>
                        </Button>
                    </Fragment>
                )}
            </Form>

            {status && <div className="mb-4 text-center text-sm font-medium text-green-600">{status}</div>}
        </Fragment>
    );
}
