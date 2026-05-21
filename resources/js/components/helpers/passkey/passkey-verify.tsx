import InputError from '@/components/helpers/input-error';
import { Button } from '@/components/ui/button';
import { Field } from '@/components/ui/field';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import { dashboard } from '@/routes';
import type { UrlMethodPair } from '@inertiajs/core';
import { router } from '@inertiajs/react';
import { usePasskeyVerify } from '@laravel/passkeys/react';
import { KeyRound } from 'lucide-react';

type Props = {
    routes?: {
        options: UrlMethodPair;
        submit: UrlMethodPair;
    };
    label?: string;
    loadingLabel?: string;
    separator?: string;
};

export default function PasskeyVerify({ routes, label, loadingLabel, separator }: Props = {}) {
    const { verify, isLoading, error, isSupported } = usePasskeyVerify({
        ...(routes && {
            routes: {
                options: routes.options.url,
                submit: routes.submit.url,
            },
        }),
        onSuccess: (response) => {
            router.visit(response.redirect ?? dashboard());
        },
    });

    if (!isSupported) {
        return null;
    }

    return (
        <>
            <Field data-invalid={!!error}>
                <Button type="button" variant="outline" className="w-full" onClick={verify} disabled={isLoading}>
                    {isLoading ? <Spinner aria-hidden /> : <KeyRound aria-hidden className="size-4" />}
                    {isLoading ? (loadingLabel ?? 'Authenticating...') : (label ?? 'Sign in with passkey')}
                </Button>
                {error && <InputError message={error} className="text-center" />}
            </Field>

            <div className="relative my-6">
                <div className="absolute inset-0 flex items-center">
                    <Separator className="w-full" />
                </div>
                <div className="relative flex justify-center text-xs uppercase">
                    <span className="bg-background px-2 text-muted-foreground">
                        {separator ?? 'Or continue with email'}
                    </span>
                </div>
            </div>
        </>
    );
}
