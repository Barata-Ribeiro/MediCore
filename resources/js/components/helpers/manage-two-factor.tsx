import Heading from '@/components/common/heading';
import TwoFactorRecoveryCodes from '@/components/helpers/two-factor-recovery-codes';
import TwoFactorSetupModal from '@/components/helpers/two-factor-setup-modal';
import { Button } from '@/components/ui/button';
import { useIsomorphicLayoutEffect } from '@/hooks/use-isomorphic-layout-effect';
import { useTwoFactorAuth } from '@/hooks/use-two-factor-auth';
import { disable, enable } from '@/routes/two-factor';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { ShieldCheckIcon } from 'lucide-react';
import { useRef, useState } from 'react';

export type Props = {
    canManageTwoFactor?: boolean;
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

export default function ManageTwoFactor(props: Readonly<Props>) {
    const { __ } = lang();
    const requiresConfirmation = props.requiresConfirmation ?? false;
    const twoFactorEnabled = props.twoFactorEnabled ?? false;

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

    if (!(props.canManageTwoFactor ?? false)) {
        return null;
    }

    return (
        <div className="space-y-6">
            <Heading
                variant="small"
                title={__('settings_pages.security_page.two_factor_authentication_section.title')}
                description={__('settings_pages.security_page.two_factor_authentication_section.description')}
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
    );
}
