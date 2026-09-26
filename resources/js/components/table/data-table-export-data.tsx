import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { RouteDefinition } from '@/wayfinder';
import { lang } from '@erag/lang-sync-inertia/react';
import { router } from '@inertiajs/react';
import { FileDownIcon, FileSpreadsheet, FileTextIcon } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';

interface DataTableExportDataProps {
    csv?: RouteDefinition<'get' | 'post'>;
    pdf?: RouteDefinition<'get'>;
}

export default function DataTableExportData({ csv, pdf }: Readonly<DataTableExportDataProps>) {
    const { __ } = lang();
    const [processing, setProcessing] = useState(false);

    return (
        <DropdownMenu>
            <DropdownMenuTrigger
                render={
                    <Button
                        variant="outline"
                        disabled={processing}
                        aria-label={__('main.data_table.export_record.label')}
                        title={__('main.data_table.export_record.label')}
                    >
                        <FileDownIcon aria-hidden />
                        {__('main.data_table.export_record.action')}
                    </Button>
                }
            />
            <DropdownMenuContent align="end" className="w-37.5">
                <DropdownMenuGroup>
                    <DropdownMenuLabel>{__('main.data_table.export_record.dropdown_label')}</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    {csv?.method === 'post' && (
                        <DropdownMenuItem
                            onClick={() =>
                                router.post(
                                    csv,
                                    {},
                                    {
                                        preserveScroll: true,
                                        onStart: () => setProcessing(true),
                                        onFinish: () => setProcessing(false),
                                        onError: () => toast.error(__('exam_csv.request_failed')),
                                    },
                                )
                            }
                        >
                            <FileSpreadsheet aria-hidden /> CSV
                        </DropdownMenuItem>
                    )}
                    {csv?.method === 'get' && (
                        <DropdownMenuItem
                            className="w-full"
                            disabled={!csv}
                            render={
                                <a
                                    href={csv.url}
                                    aria-label={__('main.data_table.export_record.csv_label')}
                                    title={__('main.data_table.export_record.csv_label')}
                                    rel="noopener noreferrer"
                                >
                                    <FileSpreadsheet aria-hidden />
                                    CSV
                                </a>
                            }
                        />
                    )}

                    {pdf && (
                        <DropdownMenuItem
                            className="w-full"
                            disabled={!pdf}
                            render={
                                <a
                                    href={pdf.url}
                                    aria-label={__('main.data_table.export_record.pdf_label')}
                                    title={__('main.data_table.export_record.pdf_label')}
                                    rel="noopener noreferrer"
                                >
                                    <FileTextIcon aria-hidden /> PDF
                                </a>
                            }
                        />
                    )}
                </DropdownMenuGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
