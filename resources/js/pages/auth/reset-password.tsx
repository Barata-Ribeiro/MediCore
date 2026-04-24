import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { update } from '@/routes/password';
import { Form, Head, setLayoutProps } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

type Props = {
    token: string;
    email: string;
};

export default function ResetPassword({ token, email }: Readonly<Props>) {
    setLayoutProps({
        title: 'Reset password',
        description: 'Please enter your new password below',
    });

    return (
        <Fragment>
            <Head title="Reset password" />

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
                            <FieldLabel htmlFor="email">Email</FieldLabel>
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
                            <FieldLabel htmlFor="password">Password</FieldLabel>
                            <PasswordInput
                                id="password"
                                name="password"
                                autoComplete="new-password"
                                className="mt-1 block w-full"
                                placeholder="Password"
                                autoFocus
                                aria-invalid={!!errors['password']}
                            />
                            <InputError message={errors['password']} />
                        </Field>

                        <Field data-invalid={!!errors['password_confirmation']}>
                            <FieldLabel htmlFor="password_confirmation">Confirm password</FieldLabel>
                            <PasswordInput
                                id="password_confirmation"
                                name="password_confirmation"
                                autoComplete="new-password"
                                className="mt-1 block w-full"
                                placeholder="Confirm password"
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
                            Reset password
                        </Button>
                    </div>
                )}
            </Form>
        </Fragment>
    );
}
