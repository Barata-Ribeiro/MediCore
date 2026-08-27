import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useClipboard } from '@/hooks/use-clipboard';
import { ClipboardCheckIcon, ClipboardIcon } from 'lucide-react';
import type { ReactNode } from 'react';
import { toast } from 'sonner';

interface RawCopyButtonProps {
    content: unknown;
    children: ReactNode;
}

export default function DropdownMenuCopyButton({ content, children }: Readonly<RawCopyButtonProps>) {
    const { copied, copy, error, reset } = useClipboard();

    const copyContentToClipboard = () => {
        copy(JSON.stringify(content));

        if (error) {
            reset();
            toast.error('Failed to copy content to clipboard');
        }

        toast.info('Copied to clipboard!', { duration: 2000 });
    };

    return (
        <DropdownMenuItem disabled={copied} onClick={copyContentToClipboard}>
            {copied ? <ClipboardCheckIcon aria-hidden size={14} /> : <ClipboardIcon aria-hidden size={14} />}
            {children}
        </DropdownMenuItem>
    );
}
