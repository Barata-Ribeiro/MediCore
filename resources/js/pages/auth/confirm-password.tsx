import { Form, Head } from '@inertiajs/react';
import { Activity } from 'react';
import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { store } from '@/routes/password/confirm';

export default function ConfirmPassword() {
    return (
        <AuthLayout
            title="Confirm your password"
            description="This is a secure area of the application. Please confirm your password before continuing."
        >
            <Head title="Confirm password" />

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                disableWhileProcessing
                className="inert:pointer-events-none inert:grayscale-100"
            >
                {({ processing, errors }) => (
                    <div className="space-y-6">
                        <Field data-invalid={!!errors['password']}>
                            <FieldLabel htmlFor="password">Password</FieldLabel>
                            <PasswordInput
                                id="password"
                                name="password"
                                placeholder="Password"
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
                                Confirm password
                            </Button>
                        </div>
                    </div>
                )}
            </Form>
        </AuthLayout>
    );
}
