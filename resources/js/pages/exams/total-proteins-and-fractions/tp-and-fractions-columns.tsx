import TotalProteinsAndFractionsController from '@/actions/App/Http/Controllers/Exams/TotalProteinsAndFractionsController';
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
import type { TotalProteinsAndFractions } from '@/types/application/exams/total-proteins-and-fractions';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({
    column,
    title,
}: Readonly<{ column: Column<TotalProteinsAndFractions, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function TotalProteinsAndFractionsValueCell({ value }: Readonly<{ value: number }>) {
    const { __ } = lang();

    return `${value} ${__('total_proteins_and_fractions_pages.shared.unit')}`;
}

function ActionsCell({
    totalProteinsAndFractions,
}: Readonly<{ totalProteinsAndFractions: TotalProteinsAndFractions }>) {
    const { __, trans } = lang();
    const [open, setOpen] = useState(false);

    const valuesToCopy = trans('total_proteins_and_fractions_pages.index.table.copy_values_content', {
        report_date: totalProteinsAndFractions.report_date,
        albumin: totalProteinsAndFractions.albumin,
        globulin: totalProteinsAndFractions.globulin,
        albumin_globulin_ratio: totalProteinsAndFractions.albumin_globulin_ratio,
        unit: __('total_proteins_and_fractions_pages.shared.unit'),
        created_at: format(totalProteinsAndFractions.created_at, 'PPP p'),
    });

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger
                    render={
                        <Button
                            aria-label={__('total_proteins_and_fractions_pages.index.table.menu.open_label')}
                            variant="ghost"
                            className="flex size-8 p-0 aria-expanded:bg-muted"
                        >
                            <EllipsisIcon aria-hidden size={16} />
                        </Button>
                    }
                />
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>
                        {__('total_proteins_and_fractions_pages.index.table.menu.copy_fields')}
                    </DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem
                            render={
                                <DropdownMenuCopyButton content={valuesToCopy}>
                                    {__('total_proteins_and_fractions_pages.index.table.menu.copy_values')}
                                </DropdownMenuCopyButton>
                            }
                        />
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuLabel>
                        {__('total_proteins_and_fractions_pages.index.table.menu.actions')}
                    </DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem
                            render={
                                <Link
                                    className="block w-full"
                                    href={TotalProteinsAndFractionsController.edit(totalProteinsAndFractions.id)}
                                    as="button"
                                >
                                    <EditIcon aria-hidden size={14} />{' '}
                                    {__('total_proteins_and_fractions_pages.index.table.menu.edit')}
                                </Link>
                            }
                        />
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} />{' '}
                            {__('total_proteins_and_fractions_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('total_proteins_and_fractions_pages.index.table.delete_dialog.title')}
                description={__('total_proteins_and_fractions_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={TotalProteinsAndFractionsController.destroy(totalProteinsAndFractions.id)}
            />
        </Fragment>
    );
}

export function useTotalProteinsAndFractionsColumns(): ColumnDef<TotalProteinsAndFractions>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'id',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('total_proteins_and_fractions_pages.index.table.columns.id')}
                />
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
                    title={__('total_proteins_and_fractions_pages.index.table.columns.report_date')}
                />
            ),
            cell: ({ row }) => format(row.original.report_date, 'PPP'),
            meta: {
                label: __('total_proteins_and_fractions_pages.index.table.columns.report_date'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            accessorKey: 'total_proteins',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('total_proteins_and_fractions_pages.index.table.columns.total_proteins')}
                />
            ),
            cell: ({ row }) => <TotalProteinsAndFractionsValueCell value={row.original.total_proteins} />,
            meta: {
                label: __('total_proteins_and_fractions_pages.index.table.columns.total_proteins'),
            },
            enableSorting: true,
        },
        {
            accessorKey: 'albumin',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('total_proteins_and_fractions_pages.index.table.columns.albumin')}
                />
            ),
            cell: ({ row }) => <TotalProteinsAndFractionsValueCell value={row.original.albumin} />,
            meta: {
                label: __('total_proteins_and_fractions_pages.index.table.columns.albumin'),
            },
            enableSorting: true,
        },
        {
            accessorKey: 'globulin',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('total_proteins_and_fractions_pages.index.table.columns.globulin')}
                />
            ),
            cell: ({ row }) => <TotalProteinsAndFractionsValueCell value={row.original.globulin} />,
            meta: {
                label: __('total_proteins_and_fractions_pages.index.table.columns.globulin'),
            },
            enableSorting: true,
        },
        {
            accessorKey: 'albumin_globulin_ratio',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('total_proteins_and_fractions_pages.index.table.columns.albumin_globulin_ratio')}
                />
            ),
            cell: ({ row }) => <TotalProteinsAndFractionsValueCell value={row.original.albumin_globulin_ratio} />,
            meta: {
                label: __('total_proteins_and_fractions_pages.index.table.columns.albumin_globulin_ratio'),
            },
            enableSorting: true,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('total_proteins_and_fractions_pages.index.table.columns.created_at')}
                />
            ),
            cell: ({ row }) => format(row.original.created_at, 'PPpp'),
            meta: {
                label: __('total_proteins_and_fractions_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell totalProteinsAndFractions={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
