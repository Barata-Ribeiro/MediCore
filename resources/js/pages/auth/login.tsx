import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import TextLink from '@/components/helpers/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { Form, Head } from '@inertiajs/react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

type Props = {
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
};

export default function Login({ status, canResetPassword, canRegister }: Readonly<Props>) {
    return (
        <AuthLayout title="Log in to your account" description="Enter your email and password below to log in">
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
                    </Fragment>
                )}
            </Form>

            {status && <div className="mb-4 text-center text-sm font-medium text-green-600">{status}</div>}
        </AuthLayout>
    );
}
