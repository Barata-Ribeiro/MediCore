import { Button } from '@/components/ui/button';
import type { RouteDefinition } from '@/wayfinder';
import { lang } from '@erag/lang-sync-inertia/react';
import { router } from '@inertiajs/react';
import { FileUpIcon } from 'lucide-react';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

export default function DataTableImportData({ route }: Readonly<{ route: RouteDefinition<'post'> }>) {
    const { __ } = lang();
    const input = useRef<HTMLInputElement>(null);
    const [processing, setProcessing] = useState(false);

    return (
        <>
            <input
                ref={input}
                type="file"
                accept=".csv,text/csv"
                className="hidden"
                aria-label={__('exam_csv.import')}
                onChange={(event) => {
                    const file = event.target.files?.[0];
                    event.target.value = '';
                    if (!file) return;

                    router.post(
                        route,
                        { file },
                        {
                            preserveScroll: true,
                            onStart: () => setProcessing(true),
                            onFinish: () => setProcessing(false),
                            onError: (errors) => toast.error(errors['file'] ?? __('exam_csv.request_failed')),
                        },
                    );
                }}
            />
            <Button
                variant="outline"
                disabled={processing}
                onClick={() => input.current?.click()}
                title={__('exam_csv.import_help')}
            >
                <FileUpIcon aria-hidden data-icon="inline-start" />
                {__('exam_csv.import')}
            </Button>
        </>
    );
}
