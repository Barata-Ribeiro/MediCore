import InputError from '@/components/helpers/input-error';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { lang } from '@erag/lang-sync-inertia/react';
import { usePasskeyRegister } from '@laravel/passkeys/react';
import { Activity, useState } from 'react';

type Props = {
    onSuccess: () => void;
};

export default function PasskeyRegistration({ onSuccess }: Readonly<Props>) {
    const { __ } = lang();
    const [name, setName] = useState(() => {
        const ua = typeof navigator === 'undefined' ? '' : navigator.userAgent;

        const browser = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'].find((browser) =>
            new RegExp(browser).test(ua),
        );

        const os = ['iPhone', 'iPad', 'Android', 'Mac', 'Windows'].find((os) => new RegExp(os).test(ua));

        return [browser, os]
            .filter(Boolean)
            .join(` ${__('settings_pages.security_page.passkeys_section.form.generated_name_connector')} `);
    });

    const [showForm, setShowForm] = useState(false);
    const { register, isLoading, error, isSupported } = usePasskeyRegister({
        onSuccess: () => {
            setName('');
            setShowForm(false);
            onSuccess();
        },
    });

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        if (!name.trim()) {
            return;
        }

        await register(name);
    };

    const handleCancel = () => {
        setShowForm(false);
        setName('');
    };

    if (!isSupported) {
        return (
            <div className="text-sm text-muted-foreground">
                {__('settings_pages.security_page.passkeys_section.form.unsupported')}
            </div>
        );
    }

    if (!showForm) {
        return (
            <Button variant="outline" onClick={() => setShowForm(true)}>
                {__('settings_pages.security_page.passkeys_section.form.add_button')}
            </Button>
        );
    }

    return (
        <form onSubmit={handleSubmit} className="space-y-4 rounded-lg border border-border bg-muted/50 p-4">
            <Field data-invalid={!!error}>
                <Label htmlFor="passkey-name">{__('settings_pages.security_page.passkeys_section.form.name')}</Label>
                <Input
                    id="passkey-name"
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    placeholder={__('settings_pages.security_page.passkeys_section.form.name_placeholder')}
                    className="mt-1 block w-full border-foreground/20"
                    autoFocus
                />

                {error ? (
                    <InputError message={error} />
                ) : (
                    <FieldDescription className="text-xs text-muted-foreground">
                        {__('settings_pages.security_page.passkeys_section.form.name_description')}
                    </FieldDescription>
                )}
            </Field>

            <div className="flex gap-2">
                <Button type="submit" disabled={isLoading || !name.trim()}>
                    <Activity mode={isLoading ? 'visible' : 'hidden'}>
                        <Spinner aria-hidden />
                    </Activity>
                    {isLoading
                        ? __('settings_pages.security_page.passkeys_section.form.submit_loading')
                        : __('settings_pages.security_page.passkeys_section.form.submit')}
                </Button>
                <Button type="button" variant="ghost" onClick={handleCancel}>
                    {__('settings_pages.security_page.passkeys_section.form.cancel')}
                </Button>
            </div>
        </form>
    );
}
