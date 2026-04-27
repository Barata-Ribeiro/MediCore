import AlertError from '@/components/helpers/alert-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { useIsomorphicLayoutEffect } from '@/hooks/use-isomorphic-layout-effect';
import { regenerateRecoveryCodes } from '@/routes/two-factor';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Eye, EyeOff, LockKeyhole, RefreshCwIcon } from 'lucide-react';
import { useCallback, useRef, useState } from 'react';

type Props = {
    recoveryCodesList: string[];
    fetchRecoveryCodes: () => Promise<void>;
    errors: string[];
};

export default function TwoFactorRecoveryCodes({ recoveryCodesList, fetchRecoveryCodes, errors }: Readonly<Props>) {
    const { __ } = lang();
    const [codesAreVisible, setCodesAreVisible] = useState<boolean>(false);
    const codesSectionRef = useRef<HTMLDivElement | null>(null);
    const canRegenerateCodes = recoveryCodesList.length > 0 && codesAreVisible;

    const toggleCodesVisibility = useCallback(async () => {
        if (!codesAreVisible && !recoveryCodesList.length) {
            await fetchRecoveryCodes();
        }

        setCodesAreVisible(!codesAreVisible);

        if (!codesAreVisible) {
            setTimeout(() => {
                codesSectionRef.current?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                });
            });
        }
    }, [codesAreVisible, recoveryCodesList.length, fetchRecoveryCodes]);

    useIsomorphicLayoutEffect(() => {
        if (!recoveryCodesList.length) {
            fetchRecoveryCodes();
        }
    }, [recoveryCodesList.length, fetchRecoveryCodes]);

    const RecoveryCodeIconComponent = codesAreVisible ? EyeOff : Eye;

    return (
        <Card>
            <CardHeader>
                <CardTitle className="flex gap-3">
                    <LockKeyhole className="size-4" aria-hidden="true" />
                    {__('settings_pages.security_page.two_factor_recovery_codes_section.card_title')}
                </CardTitle>
                <CardDescription>
                    {__('settings_pages.security_page.two_factor_recovery_codes_section.card_description')}
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div className="flex flex-col gap-3 select-none sm:flex-row sm:items-center sm:justify-between">
                    <Button
                        onClick={toggleCodesVisibility}
                        className="w-fit"
                        aria-expanded={codesAreVisible}
                        aria-controls="recovery-codes-section"
                    >
                        <RecoveryCodeIconComponent className="size-4" aria-hidden="true" />
                        {codesAreVisible
                            ? __('settings_pages.security_page.two_factor_recovery_codes_section.hide_codes_button')
                            : __('settings_pages.security_page.two_factor_recovery_codes_section.show_codes_button')}
                        {__(
                            'settings_pages.security_page.two_factor_recovery_codes_section.show_or_hide_codes_message',
                        )}
                    </Button>

                    {canRegenerateCodes && (
                        <Form
                            {...regenerateRecoveryCodes.form()}
                            options={{ preserveScroll: true }}
                            onSuccess={fetchRecoveryCodes}
                        >
                            {({ processing }) => (
                                <Button
                                    variant="secondary"
                                    type="submit"
                                    disabled={processing}
                                    aria-describedby="regenerate-warning"
                                >
                                    <RefreshCwIcon aria-hidden />{' '}
                                    {__(
                                        'settings_pages.security_page.two_factor_recovery_codes_section.regenerate_codes_button',
                                    )}
                                </Button>
                            )}
                        </Form>
                    )}
                </div>
                <div
                    id="recovery-codes-section"
                    className={`relative overflow-hidden transition-all duration-300 ${codesAreVisible ? 'h-auto opacity-100' : 'h-0 opacity-0'}`}
                    aria-hidden={!codesAreVisible}
                >
                    <div className="mt-3 space-y-3">
                        {errors?.length ? (
                            <AlertError errors={errors} />
                        ) : (
                            <>
                                <div
                                    ref={codesSectionRef}
                                    className="grid gap-1 rounded-lg bg-muted p-4 font-mono text-sm"
                                    role="list"
                                    aria-label={__(
                                        'settings_pages.security_page.two_factor_recovery_codes_section.codes_section_label',
                                    )}
                                >
                                    {recoveryCodesList.length ? (
                                        recoveryCodesList.map((code, index) => (
                                            <div key={`${code}-${index}`} role="listitem" className="select-text">
                                                {code}
                                            </div>
                                        ))
                                    ) : (
                                        <div
                                            className="space-y-2"
                                            aria-label={__(
                                                'settings_pages.security_page.two_factor_recovery_codes_section.codes_loading_message',
                                            )}
                                        >
                                            {Array.from({ length: 8 }, (_, index) => (
                                                <div
                                                    key={index}
                                                    className="h-4 animate-pulse rounded bg-muted-foreground/20"
                                                    aria-hidden="true"
                                                />
                                            ))}
                                        </div>
                                    )}
                                </div>

                                <div className="text-xs text-muted-foreground select-none">
                                    <p id="regenerate-warning">
                                        {__(
                                            'settings_pages.security_page.two_factor_recovery_codes_section.regenerate_warning_message',
                                        )}{' '}
                                        <span className="font-bold">
                                            {__(
                                                'settings_pages.security_page.two_factor_recovery_codes_section.regenerate_warning_message_span',
                                            )}
                                        </span>{' '}
                                        {__(
                                            'settings_pages.security_page.two_factor_recovery_codes_section.regenerate_warning_message_end',
                                        )}
                                    </p>
                                </div>
                            </>
                        )}
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
