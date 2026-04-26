import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/common/heading';
import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import TwoFactorRecoveryCodes from '@/components/helpers/two-factor-recovery-codes';
import TwoFactorSetupModal from '@/components/helpers/two-factor-setup-modal';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import { useIsomorphicLayoutEffect } from '@/hooks/use-isomorphic-layout-effect';
import { useTwoFactorAuth } from '@/hooks/use-two-factor-auth';
import { edit } from '@/routes/security';
import { disable, enable } from '@/routes/two-factor';
import { lang } from '@erag/lang-sync-inertia/react';
import { Transition } from '@headlessui/react';
import { Form, Head, setLayoutProps } from '@inertiajs/react';
import { ShieldCheckIcon } from 'lucide-react';
import { Activity, Fragment, useRef, useState } from 'react';

type Props = {
    canManageTwoFactor?: boolean;
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

export default function Security({
    canManageTwoFactor = false,
    requiresConfirmation = false,
    twoFactorEnabled = false,
}: Readonly<Props>) {
    const { __ } = lang();
    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    setLayoutProps({
        breadcrumbs: [{ title: __('settings_pages.security_page.head_title'), href: edit() }],
    });

    const {
        qrCodeSvg,
        hasSetupData,
        manualSetupKey,
        clearSetupData,
        clearTwoFactorAuthData,
        fetchSetupData,
        recoveryCodesList,
        fetchRecoveryCodes,
        errors,
    } = useTwoFactorAuth();
    const [showSetupModal, setShowSetupModal] = useState<boolean>(false);
    const prevTwoFactorEnabled = useRef(twoFactorEnabled);

    useIsomorphicLayoutEffect(() => {
        if (prevTwoFactorEnabled.current && !twoFactorEnabled) {
            clearTwoFactorAuthData();
        }

        prevTwoFactorEnabled.current = twoFactorEnabled;
    }, [twoFactorEnabled, clearTwoFactorAuthData]);

    return (
        <Fragment>
            <Head title={__('settings_pages.security_page.head_title')} />

            <h1 className="sr-only">{__('settings_pages.security_page.head_title')}</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title={__('settings_pages.security_page.password_section.title')}
                    description={__('settings_pages.security_page.password_section.description')}
                />

                <Form
                    {...SecurityController.update.form()}
                    options={{ preserveScroll: true }}
                    resetOnError={['password', 'password_confirmation', 'current_password']}
                    resetOnSuccess
                    onError={(errors) => {
                        if (errors['password']) {
                            passwordInput.current?.focus();
                        }

                        if (errors['current_password']) {
                            currentPasswordInput.current?.focus();
                        }
                    }}
                    disableWhileProcessing
                    className="space-y-6 inert:pointer-events-none inert:grayscale-100"
                >
                    {({ errors, processing, recentlySuccessful }) => (
                        <Fragment>
                            <Field data-invalid={!!errors['current_password']}>
                                <FieldLabel htmlFor="current_password">
                                    {__('settings_pages.security_page.password_section.form.current_password')}
                                </FieldLabel>

                                <PasswordInput
                                    id="current_password"
                                    ref={currentPasswordInput}
                                    name="current_password"
                                    className="mt-1 block w-full"
                                    autoComplete="current-password"
                                    placeholder={__(
                                        'settings_pages.security_page.password_section.form.current_password_placeholder',
                                    )}
                                    aria-invalid={!!errors['current_password']}
                                />

                                <InputError message={errors['current_password']} />
                            </Field>

                            <Field data-invalid={!!errors['password']}>
                                <FieldLabel htmlFor="password">
                                    {__('settings_pages.security_page.password_section.form.new_password')}
                                </FieldLabel>

                                <PasswordInput
                                    id="password"
                                    ref={passwordInput}
                                    name="password"
                                    className="mt-1 block w-full"
                                    autoComplete="new-password"
                                    placeholder={__(
                                        'settings_pages.security_page.password_section.form.new_password_placeholder',
                                    )}
                                    aria-invalid={!!errors['password']}
                                />

                                <InputError message={errors['password']} />
                            </Field>

                            <Field data-invalid={!!errors['password_confirmation']}>
                                <FieldLabel htmlFor="password_confirmation">
                                    {__('settings_pages.security_page.password_section.form.confirm_password')}
                                </FieldLabel>

                                <PasswordInput
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    className="mt-1 block w-full"
                                    autoComplete="new-password"
                                    placeholder={__(
                                        'settings_pages.security_page.password_section.form.confirm_password_placeholder',
                                    )}
                                    aria-invalid={!!errors['password_confirmation']}
                                />

                                <InputError message={errors['password_confirmation']} />
                            </Field>

                            <div className="flex items-center gap-4">
                                <Button data-test="update-password-button">
                                    <Activity mode={processing ? 'visible' : 'hidden'}>
                                        <Spinner aria-hidden />
                                    </Activity>
                                    {__('settings_pages.security_page.password_section.form.submit')}
                                </Button>

                                <Transition
                                    show={recentlySuccessful}
                                    enter="transition ease-in-out"
                                    enterFrom="opacity-0"
                                    leave="transition ease-in-out"
                                    leaveTo="opacity-0"
                                >
                                    <p className="text-sm text-neutral-600">
                                        {__('settings_pages.security_page.password_section.form.success_message')}
                                    </p>
                                </Transition>
                            </div>
                        </Fragment>
                    )}
                </Form>
            </div>

            <Activity mode={canManageTwoFactor ? 'visible' : 'hidden'}>
                <div className="space-y-6">
                    <Heading
                        variant="small"
                        title="Two-factor authentication"
                        description="Manage your two-factor authentication settings"
                    />
                    {twoFactorEnabled ? (
                        <div className="flex flex-col items-start justify-start space-y-4">
                            <p className="text-sm text-muted-foreground">
                                {__(
                                    'settings_pages.security_page.two_factor_authentication_section.two_factor_authentication_enabled',
                                )}
                            </p>

                            <div className="relative inline">
                                <Form {...disable.form()}>
                                    {({ processing }) => (
                                        <Button variant="destructive" type="submit" disabled={processing}>
                                            {__(
                                                'settings_pages.security_page.two_factor_authentication_section.two_factor_authentication_enabled_button',
                                            )}
                                        </Button>
                                    )}
                                </Form>
                            </div>

                            <TwoFactorRecoveryCodes
                                recoveryCodesList={recoveryCodesList}
                                fetchRecoveryCodes={fetchRecoveryCodes}
                                errors={errors}
                            />
                        </div>
                    ) : (
                        <div className="flex flex-col items-start justify-start space-y-4">
                            <p className="text-sm text-muted-foreground">
                                {__(
                                    'settings_pages.security_page.two_factor_authentication_section.two_factor_authentication_disabled',
                                )}
                            </p>

                            <div>
                                {hasSetupData ? (
                                    <Button onClick={() => setShowSetupModal(true)}>
                                        <ShieldCheckIcon aria-hidden />
                                        {__(
                                            'settings_pages.security_page.two_factor_authentication_section.two_factor_authentication_has_setup_data',
                                        )}
                                    </Button>
                                ) : (
                                    <Form {...enable.form()} onSuccess={() => setShowSetupModal(true)}>
                                        {({ processing }) => (
                                            <Button type="submit" disabled={processing}>
                                                {__(
                                                    'settings_pages.security_page.two_factor_authentication_section.two_factor_authentication_disabled_button',
                                                )}
                                            </Button>
                                        )}
                                    </Form>
                                )}
                            </div>
                        </div>
                    )}

                    <TwoFactorSetupModal
                        isOpen={showSetupModal}
                        onClose={() => setShowSetupModal(false)}
                        requiresConfirmation={requiresConfirmation}
                        twoFactorEnabled={twoFactorEnabled}
                        qrCodeSvg={qrCodeSvg}
                        manualSetupKey={manualSetupKey}
                        clearSetupData={clearSetupData}
                        fetchSetupData={fetchSetupData}
                        errors={errors}
                    />
                </div>
            </Activity>
        </Fragment>
    );
}
