import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import type { Passkey } from '@/types/auth';
import { lang } from '@erag/lang-sync-inertia/react';
import { KeyRound, Trash2 } from 'lucide-react';
import { Activity, useState } from 'react';

type Props = {
    passkey: Passkey;
    onDelete: (id: number, onError: () => void) => void;
};

export default function PasskeyItem({ passkey, onDelete }: Readonly<Props>) {
    const { __ } = lang();
    const [isDeleting, setIsDeleting] = useState(false);

    const handleDelete = () => {
        setIsDeleting(true);
        onDelete(passkey.id, () => setIsDeleting(false));
    };

    return (
        <div className="flex items-center justify-between border-b p-4 last:border-b-0">
            <div className="flex items-center gap-4">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-muted">
                    <KeyRound aria-hidden className="size-5 text-muted-foreground" />
                </div>
                <div className="space-y-1">
                    <div className="flex items-center gap-2.5">
                        <p className="font-medium tracking-tight">{passkey.name}</p>
                        {passkey.authenticator && (
                            <Badge variant="secondary" className="ring-1 ring-border ring-inset">
                                {passkey.authenticator}
                            </Badge>
                        )}
                    </div>
                    <p className="text-sm text-muted-foreground">
                        {__('settings_pages.security_page.passkeys_section.added', {
                            time: passkey.created_at_diff,
                        })}
                        {passkey.last_used_at_diff && (
                            <>
                                <span className="mx-1 text-muted-foreground/50">/</span>
                                {__('settings_pages.security_page.passkeys_section.last_used', {
                                    time: passkey.last_used_at_diff,
                                })}
                            </>
                        )}
                    </p>
                </div>
            </div>

            <Dialog>
                <DialogTrigger asChild>
                    <Button variant="destructive" size="sm">
                        <Trash2 aria-hidden className="size-4" />
                        <span className="sr-only">
                            {__('settings_pages.security_page.passkeys_section.remove_sr_label')}
                        </span>
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogTitle>{__('settings_pages.security_page.passkeys_section.remove_modal.title')}</DialogTitle>
                    <DialogDescription>
                        {__('settings_pages.security_page.passkeys_section.remove_modal.description', {
                            name: passkey.name,
                        })}
                    </DialogDescription>
                    <DialogFooter className="gap-2">
                        <DialogClose asChild>
                            <Button variant="secondary">
                                {__('settings_pages.security_page.passkeys_section.remove_modal.cancel')}
                            </Button>
                        </DialogClose>
                        <Button variant="destructive" onClick={handleDelete} disabled={isDeleting}>
                            <Activity mode={isDeleting ? 'visible' : 'hidden'}>
                                <Spinner aria-hidden />
                            </Activity>
                            {isDeleting
                                ? __('settings_pages.security_page.passkeys_section.remove_modal.submit_loading')
                                : __('settings_pages.security_page.passkeys_section.remove_modal.submit')}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
