import GlucoseController from '@/actions/App/Http/Controllers/Exams/GlucoseController';
import ActionConfirmationDialog from '@/components/common/action-confirmation-dialog';
import DropdownMenuCopyButton from '@/components/common/dropdown-menu-copy-button';
import DataTableColumnHeader from '@/components/table/data-table-column-header';
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
import type { Glucose } from '@/types/application/exams/glucose';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns/format';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({ column, title }: Readonly<{ column: Column<Glucose, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function GlucoseValueCell({ value }: Readonly<{ value: number }>) {
    const { __ } = lang();

    return `${value} ${__('glucose_pages.shared.unit')}`;
}

function PercentageValueCell({ value }: Readonly<{ value: number }>) {
    const { __ } = lang();

    return `${value}${__('glucose_pages.shared.percentage_unit')}`;
}

function ActionsCell({ glucose }: Readonly<{ glucose: Glucose }>) {
    const { __, trans } = lang();
    const [open, setOpen] = useState(false);

    const valuesToCopy = trans('glucose_pages.index.table.copy_values_content', {
        report_date: format(glucose.report_date, 'PPP'),
        glucose_level: glucose.glucose_level,
        glycated_hemoglobin: glucose.glycated_hemoglobin,
        estimated_average_glucose: glucose.estimated_average_glucose,
        unit: __('glucose_pages.shared.unit'),
        percentage_unit: __('glucose_pages.shared.percentage_unit'),
        created_at: format(glucose.created_at, 'PPP p'),
    });

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger asChild>
                    <Button
                        aria-label={__('glucose_pages.index.table.menu.open_label')}
                        variant="ghost"
                        className="flex size-8 p-0 data-[state=open]:bg-muted"
                    >
                        <EllipsisIcon aria-hidden size={16} />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>{__('glucose_pages.index.table.menu.copy_fields')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <DropdownMenuCopyButton content={valuesToCopy}>
                                {__('glucose_pages.index.table.menu.copy_values')}
                            </DropdownMenuCopyButton>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuLabel>{__('glucose_pages.index.table.menu.actions')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <Link className="block w-full" href={GlucoseController.edit(glucose.id)} as="button">
                                <EditIcon aria-hidden size={14} /> {__('glucose_pages.index.table.menu.edit')}
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} /> {__('glucose_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('glucose_pages.index.table.delete_dialog.title')}
                description={__('glucose_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={GlucoseController.destroy(glucose.id)}
            />
        </Fragment>
    );
}

export function useGlucoseColumns(): ColumnDef<Glucose>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'id',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('glucose_pages.index.table.columns.id')} />
            ),
            enableSorting: true,
            enableHiding: false,
            size: 40,
        },
        {
            accessorKey: 'report_date',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('glucose_pages.index.table.columns.report_date')} />
            ),
            cell: ({ row }) => format(row.original.report_date, 'PPP'),
            meta: {
                label: __('glucose_pages.index.table.columns.report_date'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            accessorKey: 'glucose_level',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('glucose_pages.index.table.columns.glucose_level')} />
            ),
            cell: ({ row }) => <GlucoseValueCell value={row.original.glucose_level} />,
            enableSorting: true,
        },
        {
            accessorKey: 'glycated_hemoglobin',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('glucose_pages.index.table.columns.glycated_hemoglobin')}
                />
            ),
            cell: ({ row }) => <PercentageValueCell value={row.original.glycated_hemoglobin} />,
            enableSorting: true,
        },
        {
            accessorKey: 'estimated_average_glucose',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('glucose_pages.index.table.columns.estimated_average_glucose')}
                />
            ),
            cell: ({ row }) => <GlucoseValueCell value={row.original.estimated_average_glucose} />,
            enableSorting: true,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('glucose_pages.index.table.columns.created_at')} />
            ),
            cell: ({ row }) => format(row.original.created_at, 'PPP p'),
            meta: {
                label: __('glucose_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell glucose={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
