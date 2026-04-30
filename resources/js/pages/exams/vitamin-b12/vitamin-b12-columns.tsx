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
import { destroy, edit } from '@/routes/vitamin-b12';
import type { VitaminB12 } from '@/types/application/exams/vitamin-b12';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns/format';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({ column, title }: Readonly<{ column: Column<VitaminB12, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function VitaminB12ValueCell({ value }: Readonly<{ value: number }>) {
    const { __ } = lang();

    return `${value} ${__('vitamin_b12_pages.shared.unit')}`;
}

function ActionsCell({ vitaminB12 }: Readonly<{ vitaminB12: VitaminB12 }>) {
    const { __, trans } = lang();
    const [open, setOpen] = useState(false);

    const valuesToCopy = trans('vitamin_b12_pages.index.table.copy_values_content', {
        report_date: format(vitaminB12.report_date, 'PPP'),
        vitamin_b12: vitaminB12.vitamin_b12_level,
        unit: __('vitamin_b12_pages.shared.unit'),
        created_at: format(vitaminB12.created_at, 'PPP p'),
    });

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger asChild>
                    <Button
                        aria-label={__('vitamin_b12_pages.index.table.menu.open_label')}
                        variant="ghost"
                        className="flex size-8 p-0 data-[state=open]:bg-muted"
                    >
                        <EllipsisIcon aria-hidden size={16} />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>{__('vitamin_b12_pages.index.table.menu.copy_fields')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <DropdownMenuCopyButton content={valuesToCopy}>
                                {__('vitamin_b12_pages.index.table.menu.copy_values')}
                            </DropdownMenuCopyButton>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuLabel>{__('vitamin_b12_pages.index.table.menu.actions')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <Link className="block w-full" href={edit(vitaminB12.id)} as="button">
                                <EditIcon aria-hidden size={14} /> {__('vitamin_b12_pages.index.table.menu.edit')}
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} /> {__('vitamin_b12_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('vitamin_b12_pages.index.table.delete_dialog.title')}
                description={__('vitamin_b12_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={destroy(vitaminB12.id)}
            />
        </Fragment>
    );
}

export function useVitaminB12Columns(): ColumnDef<VitaminB12>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'id',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('vitamin_b12_pages.index.table.columns.id')} />
            ),
            enableSorting: true,
            enableHiding: false,
            size: 40,
        },
        {
            accessorKey: 'report_date',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('vitamin_b12_pages.index.table.columns.report_date')} />
            ),
            cell: ({ row }) => format(row.original.report_date, 'PPP'),
            meta: {
                label: __('vitamin_b12_pages.index.table.columns.report_date'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            accessorKey: 'vitamin_b12_level',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('vitamin_b12_pages.index.table.columns.vitamin_b12')} />
            ),
            cell: ({ row }) => <VitaminB12ValueCell value={row.original.vitamin_b12_level} />,
            enableSorting: true,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('vitamin_b12_pages.index.table.columns.created_at')} />
            ),
            cell: ({ row }) => format(row.original.created_at, 'PPpp'),
            meta: {
                label: __('vitamin_b12_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell vitaminB12={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
