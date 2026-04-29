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
import { destroy, edit } from '@/routes/complete-blood-count';
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import type { Column, ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

function TableColumnHeader({
    column,
    title,
}: Readonly<{ column: Column<CompleteBloodCount, unknown>; title: string }>) {
    return <DataTableColumnHeader column={column} title={title} />;
}

function ActionsCell({ completeBloodCount }: Readonly<{ completeBloodCount: CompleteBloodCount }>) {
    const { __, trans } = lang();
    const [open, setOpen] = useState(false);

    const valuesToCopy = trans('complete_blood_count_pages.index.table.copy_values_content', {
        report_date: format(completeBloodCount.report_date, 'PPP'),
        hematocrit: completeBloodCount.hematocrit,
        hemoglobin: completeBloodCount.hemoglobin,
        red_blood_cell_count: completeBloodCount.red_blood_cell_count,
        mean_corpuscular_volume: completeBloodCount.mean_corpuscular_volume,
        mean_corpuscular_hemoglobin: completeBloodCount.mean_corpuscular_hemoglobin,
        mean_corpuscular_hemoglobin_concentration: completeBloodCount.mean_corpuscular_hemoglobin_concentration,
        red_blood_cell_distribution_width: completeBloodCount.red_blood_cell_distribution_width,
        leukocyte_count: completeBloodCount.leukocyte_count,
        rod_neutrophil_count: completeBloodCount.rod_neutrophil_count,
        segmented_neutrophil_count: completeBloodCount.segmented_neutrophil_count,
        lymphocyte_count: completeBloodCount.lymphocyte_count,
        monocyte_count: completeBloodCount.monocyte_count,
        eosinophil_count: completeBloodCount.eosinophil_count,
        basophil_count: completeBloodCount.basophil_count,
        metamyelocyte_count: completeBloodCount.metamyelocyte_count,
        promyelocyte_count: completeBloodCount.promyelocyte_count,
        atypical_cell_count: completeBloodCount.atypical_cell_count,
        platelet_count: completeBloodCount.platelet_count,
        created_at: format(completeBloodCount.created_at, 'PPP p'),
    });

    return (
        <Fragment>
            <DropdownMenu modal={false}>
                <DropdownMenuTrigger asChild>
                    <Button
                        aria-label={__('complete_blood_count_pages.index.table.menu.open_label')}
                        variant="ghost"
                        className="flex size-8 p-0 data-[state=open]:bg-muted"
                    >
                        <EllipsisIcon aria-hidden size={16} />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-40">
                    <DropdownMenuLabel>
                        {__('complete_blood_count_pages.index.table.menu.copy_fields')}
                    </DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <DropdownMenuCopyButton content={valuesToCopy}>
                                {__('complete_blood_count_pages.index.table.menu.copy_values')}
                            </DropdownMenuCopyButton>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuLabel>{__('complete_blood_count_pages.index.table.menu.actions')}</DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem asChild>
                            <Link className="block w-full" href={edit(completeBloodCount.id)} as="button">
                                <EditIcon aria-hidden size={14} />{' '}
                                {__('complete_blood_count_pages.index.table.menu.edit')}
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                            <DeleteIcon aria-hidden size={14} />{' '}
                            {__('complete_blood_count_pages.index.table.menu.delete')}
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>

            <ActionConfirmationDialog
                title={__('complete_blood_count_pages.index.table.delete_dialog.title')}
                description={__('complete_blood_count_pages.index.table.delete_dialog.description')}
                open={open}
                setOpen={setOpen}
                method="delete"
                route={destroy(completeBloodCount.id)}
            />
        </Fragment>
    );
}

export function useCompleteBloodCountColumns(): ColumnDef<CompleteBloodCount>[] {
    const { __ } = lang();

    return [
        {
            accessorKey: 'id',
            header: ({ column }) => (
                <TableColumnHeader column={column} title={__('complete_blood_count_pages.index.table.columns.id')} />
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
                    title={__('complete_blood_count_pages.index.table.columns.report_date')}
                />
            ),
            cell: ({ row }) => format(row.original.report_date, 'PPP'),
            meta: {
                label: __('complete_blood_count_pages.index.table.columns.report_date'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            accessorKey: 'hematocrit',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.hematocrit')}
                />
            ),
            cell: ({ row }) => `${row.original.hematocrit}%`,
            enableSorting: true,
        },
        {
            accessorKey: 'hemoglobin',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.hemoglobin')}
                />
            ),
            cell: ({ row }) => `${row.original.hemoglobin} g/dL`,
            enableSorting: true,
        },
        {
            accessorKey: 'red_blood_cell_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.red_blood_cell_count')}
                />
            ),
            cell: ({ row }) => `${row.original.red_blood_cell_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'mean_corpuscular_volume',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.mean_corpuscular_volume')}
                />
            ),
            cell: ({ row }) => `${row.original.mean_corpuscular_volume} fL`,
            enableSorting: true,
        },
        {
            accessorKey: 'mean_corpuscular_hemoglobin',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.mean_corpuscular_hemoglobin')}
                />
            ),
            cell: ({ row }) => `${row.original.mean_corpuscular_hemoglobin} pg`,
            enableSorting: true,
        },
        {
            accessorKey: 'mean_corpuscular_hemoglobin_concentration',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__(
                        'complete_blood_count_pages.index.table.columns.mean_corpuscular_hemoglobin_concentration',
                    )}
                />
            ),
            cell: ({ row }) => `${row.original.mean_corpuscular_hemoglobin_concentration} g/dL`,
            enableSorting: true,
        },
        {
            accessorKey: 'red_blood_cell_distribution_width',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.red_blood_cell_distribution_width')}
                />
            ),
            cell: ({ row }) => `${row.original.red_blood_cell_distribution_width}%`,
            enableSorting: true,
        },
        {
            accessorKey: 'leukocyte_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.leukocyte_count')}
                />
            ),
            cell: ({ row }) => `${row.original.leukocyte_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'rod_neutrophil_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.rod_neutrophil_count')}
                />
            ),
            cell: ({ row }) => `${row.original.rod_neutrophil_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'segmented_neutrophil_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.segmented_neutrophil_count')}
                />
            ),
            cell: ({ row }) => `${row.original.segmented_neutrophil_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'lymphocyte_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.lymphocyte_count')}
                />
            ),
            cell: ({ row }) => `${row.original.lymphocyte_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'monocyte_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.monocyte_count')}
                />
            ),
            cell: ({ row }) => `${row.original.monocyte_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'eosinophil_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.eosinophil_count')}
                />
            ),
            cell: ({ row }) => `${row.original.eosinophil_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'basophil_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.basophil_count')}
                />
            ),
            cell: ({ row }) => `${row.original.basophil_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'metamyelocyte_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.metamyelocyte_count')}
                />
            ),
            cell: ({ row }) => `${row.original.metamyelocyte_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'promyelocyte_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.promyelocyte_count')}
                />
            ),
            cell: ({ row }) => `${row.original.promyelocyte_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'atypical_cell_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.atypical_cell_count')}
                />
            ),
            cell: ({ row }) => `${row.original.atypical_cell_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'platelet_count',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.platelet_count')}
                />
            ),
            cell: ({ row }) => `${row.original.platelet_count} /mm`,
            enableSorting: true,
        },
        {
            accessorKey: 'created_at',
            header: ({ column }) => (
                <TableColumnHeader
                    column={column}
                    title={__('complete_blood_count_pages.index.table.columns.created_at')}
                />
            ),
            cell: ({ row }) => format(row.original.created_at, 'PPpp'),
            meta: {
                label: __('complete_blood_count_pages.index.table.columns.created_at'),
                variant: 'dateRange',
                icon: CalendarIcon,
            },
            enableSorting: true,
        },
        {
            id: 'actions',
            cell: ({ row }) => <ActionsCell completeBloodCount={row.original} />,
            size: 40,
            enableHiding: false,
        },
    ];
}
