import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import InputError from '@/components/helpers/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { send } from '@/routes/verification';
import { Transition } from '@headlessui/react';
import { Form, Link, usePage } from '@inertiajs/react';
import { ShieldUserIcon } from 'lucide-react';
import { Activity, Fragment } from 'react';

type Props = {
    mustVerifyEmail: boolean;
    status: string | undefined;
};

export default function BaseAccountUpdateForm({ mustVerifyEmail, status }: Readonly<Props>) {
    const { auth } = usePage().props;

    const isSuperAdmin = auth.user.roles.some((role) => role.name === 'super-admin');

    return (
        <Form
            {...ProfileController.update.form()}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, recentlySuccessful, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['name']}>
                        <FieldLabel htmlFor="name" className="inline-flex items-center gap-x-1">
                            <span>Name</span>
                            <Activity mode={isSuperAdmin ? 'visible' : 'hidden'}>
                                <Badge>
                                    Super Admin <ShieldUserIcon data-icon="inline-end" aria-hidden />
                                </Badge>
                            </Activity>
                        </FieldLabel>

                        <Input
                            id="name"
                            className="mt-1 block w-full"
                            defaultValue={auth.user.name}
                            name="name"
                            required
                            autoComplete="name"
                            placeholder="Full name"
                            aria-invalid={!!errors['name']}
                        />

                        <InputError className="mt-2" message={errors['name']} />
                    </Field>

                    <Field data-invalid={!!errors['email']}>
                        <FieldLabel htmlFor="email">Email address</FieldLabel>

                        <Input
                            id="email"
                            type="email"
                            className="mt-1 block w-full"
                            defaultValue={auth.user.email}
                            name="email"
                            required
                            autoComplete="username"
                            placeholder="Email address"
                            aria-invalid={!!errors['email']}
                        />

                        <InputError className="mt-2" message={errors['email']} />
                    </Field>

                    <Activity mode={mustVerifyEmail && auth.user.email_verified_at === null ? 'visible' : 'hidden'}>
                        <div>
                            <p className="-mt-4 text-sm text-muted-foreground">
                                Your email address is unverified.{' '}
                                <Link
                                    href={send()}
                                    as="button"
                                    className="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                >
                                    Click here to resend the verification email.
                                </Link>
                            </p>

                            <Activity mode={status === 'verification-link-sent' ? 'visible' : 'hidden'}>
                                <div className="mt-2 text-sm font-medium text-green-600">
                                    A new verification link has been sent to your email address.
                                </div>
                            </Activity>
                        </div>
                    </Activity>

                    <div className="flex items-center gap-4">
                        <Button data-test="update-profile-button">
                            <Activity mode={processing ? 'visible' : 'hidden'}>
                                <Spinner aria-hidden />
                            </Activity>
                            Save
                        </Button>

                        <Transition
                            show={recentlySuccessful}
                            enter="transition ease-in-out"
                            enterFrom="opacity-0"
                            leave="transition ease-in-out"
                            leaveTo="opacity-0"
                        >
                            <p className="text-sm text-neutral-600">Saved</p>
                        </Transition>
                    </div>
                </Fragment>
            )}
        </Form>
    );
}
