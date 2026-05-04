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
import { destroy, edit } from '@/routes/urea-and-creatinine';
import type { UreaAndCreatinine } from '@/types/application/exams/urea-and-creatinine';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns/format';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({ column, title }: Readonly<{ column: Column<UreaAndCreatinine, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function UreaAndCreatinineValueCell({ value }: Readonly<{ value: number }>) {
    const { __ } = lang();

    return `${value} ${__('urea_and_creatinine_pages.shared.unit')}`;
}

function ActionsCell({ ureaAndCreatinine }: Readonly<{ ureaAndCreatinine: UreaAndCreatinine }>) {
    const { __, trans } = lang();
    const [open, setOpen] = useState(false);

    const valuesToCopy = trans('urea_and_creatinine_pages.index.table.copy_values_content', {
        report_date: format(ureaAndCreatinine.report_date, 'PPP'),
        urea_level: ureaAndCreatinine.urea_level,
        creatinine_level: ureaAndCreatinine.creatinine_level,
        unit: __('urea_and_creatinine_pages.shared.unit'),
        created_at: format(ureaAndCreatinine.created_at, 'PPP p'),
    });

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger asChild>
                    <Button
                        aria-label={__('urea_and_creatinine_pages.index.table.menu.open_label')}
                        variant="ghost"
                        className="flex size-8 p-0 data-[state=open]:bg-muted"
                    >
                        <EllipsisIcon aria-hidden size={16} />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>
                        {__('urea_and_creatinine_pages.index.table.menu.copy_fields')}
                    </DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <DropdownMenuCopyButton content={valuesToCopy}>
                                {__('urea_and_creatinine_pages.index.table.menu.copy_values')}
                            </DropdownMenuCopyButton>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuLabel>{__('urea_and_creatinine_pages.index.table.menu.actions')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <Link className="block w-full" href={edit(ureaAndCreatinine.id)} as="button">
                                <EditIcon aria-hidden size={14} />{' '}
                                {__('urea_and_creatinine_pages.index.table.menu.edit')}
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} />{' '}
                            {__('urea_and_creatinine_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('urea_and_creatinine_pages.index.table.delete_dialog.title')}
                description={__('urea_and_creatinine_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={destroy(ureaAndCreatinine.id)}
            />
        </Fragment>
    );
}

export function useUreaAndCreatinineColumns(): ColumnDef<UreaAndCreatinine>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'id',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('urea_and_creatinine_pages.index.table.columns.id')} />
            ),
            enableSorting: true,
            enableHiding: false,
            size: 40,
        },
        {
            accessorKey: 'report_date',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('urea_and_creatinine_pages.index.table.columns.report_date')}
                />
            ),
            cell: ({ row }) => format(row.original.report_date, 'PPP'),
            meta: {
                label: __('urea_and_creatinine_pages.index.table.columns.report_date'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            accessorKey: 'urea_level',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('urea_and_creatinine_pages.index.table.columns.urea_level')}
                />
            ),
            cell: ({ row }) => <UreaAndCreatinineValueCell value={row.original.urea_level} />,
            enableSorting: true,
        },
        {
            accessorKey: 'creatinine_level',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('urea_and_creatinine_pages.index.table.columns.creatinine_level')}
                />
            ),
            cell: ({ row }) => <UreaAndCreatinineValueCell value={row.original.creatinine_level} />,
            enableSorting: true,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('urea_and_creatinine_pages.index.table.columns.created_at')}
                />
            ),
            cell: ({ row }) => format(row.original.created_at, 'PPpp'),
            meta: {
                label: __('urea_and_creatinine_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell ureaAndCreatinine={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
