import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { RouteDefinition } from '@/wayfinder';
import { lang } from '@erag/lang-sync-inertia/react';
import { FileDownIcon, FileSpreadsheet, FileTextIcon } from 'lucide-react';

interface DataTableExportDataProps {
    csv?: RouteDefinition<'get'>;
    pdf?: RouteDefinition<'get'>;
}

export default function DataTableExportData({ csv, pdf }: Readonly<DataTableExportDataProps>) {
    const { __ } = lang();

    return (
        <DropdownMenu>
            <DropdownMenuTrigger
                render={
                    <Button
                        variant="outline"
                        aria-label={__('main.data_table.export_record.label')}
                        title={__('main.data_table.export_record.label')}
                    >
                        <FileDownIcon aria-hidden />
                        {__('main.data_table.export_record.action')}
                    </Button>
                }
            />
            <DropdownMenuContent align="end" className="w-37.5">
                <DropdownMenuLabel>{__('main.data_table.export_record.dropdown_label')}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                {csv && (
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
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
