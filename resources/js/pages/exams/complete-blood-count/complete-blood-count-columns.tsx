import CompleteBloodCountController from '@/actions/App/Http/Controllers/Exams/CompleteBloodCountController';
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
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import { Link } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns';
import { CalendarIcon, DeleteIcon, EditIcon, EllipsisIcon } from 'lucide-react';
import { Fragment, useState } from 'react';

export const columns: ColumnDef<CompleteBloodCount>[] = [
    {
        accessorKey: 'id',
        header: ({ column }) => <DataTableColumnHeader column={column} title="ID" />,
        enableSorting: true,
        enableHiding: false,
        size: 40,
    },
    {
        accessorKey: 'report_date',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Report Date" />,
        cell: ({ row }) => format(row.original.report_date, 'PPP'),
        meta: {
            label: 'Report Date',
            variant: 'dateRange',
            icon: CalendarIcon,
        },
        enableSorting: true,
    },
    {
        accessorKey: 'hematocrit',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Hematocrit" />,
        cell: ({ row }) => `${row.original.hematocrit}%`,
        enableSorting: true,
    },
    {
        accessorKey: 'hemoglobin',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Hemoglobin" />,
        cell: ({ row }) => `${row.original.hemoglobin} g/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'red_blood_cell_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="RBC" />,
        cell: ({ row }) => `${row.original.red_blood_cell_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'mean_corpuscular_volume',
        header: ({ column }) => <DataTableColumnHeader column={column} title="MCV" />,
        cell: ({ row }) => `${row.original.mean_corpuscular_volume} fL`,
        enableSorting: true,
    },
    {
        accessorKey: 'mean_corpuscular_hemoglobin',
        header: ({ column }) => <DataTableColumnHeader column={column} title="MCH" />,
        cell: ({ row }) => `${row.original.mean_corpuscular_hemoglobin} pg`,
        enableSorting: true,
    },
    {
        accessorKey: 'mean_corpuscular_hemoglobin_concentration',
        header: ({ column }) => <DataTableColumnHeader column={column} title="MCHC" />,
        cell: ({ row }) => `${row.original.mean_corpuscular_hemoglobin_concentration} g/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'red_blood_cell_distribution_width',
        header: ({ column }) => <DataTableColumnHeader column={column} title="RDW" />,
        cell: ({ row }) => `${row.original.red_blood_cell_distribution_width}%`,
        enableSorting: true,
    },
    {
        accessorKey: 'leukocyte_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="WBC" />,
        cell: ({ row }) => `${row.original.leukocyte_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'rod_neutrophil_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Rod Neutrophil Count" />,
        cell: ({ row }) => `${row.original.rod_neutrophil_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'segmented_neutrophil_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Segmented Neutrophil Count" />,
        cell: ({ row }) => `${row.original.segmented_neutrophil_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'lymphocyte_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Lymphocyte Count" />,
        cell: ({ row }) => `${row.original.lymphocyte_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'monocyte_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Monocyte Count" />,
        cell: ({ row }) => `${row.original.monocyte_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'eosinophil_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Eosinophil Count" />,
        cell: ({ row }) => `${row.original.eosinophil_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'basophil_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Basophil Count" />,
        cell: ({ row }) => `${row.original.basophil_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'metamyelocyte_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Metamyelocyte Count" />,
        cell: ({ row }) => `${row.original.metamyelocyte_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'promyelocyte_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Promyelocyte Count" />,
        cell: ({ row }) => `${row.original.promyelocyte_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'atypical_cell_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Atypical Cell Count" />,
        cell: ({ row }) => `${row.original.atypical_cell_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'platelet_count',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Platelet Count" />,
        cell: ({ row }) => `${row.original.platelet_count} /mm`,
        enableSorting: true,
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Created At" />,
        cell: ({ row }) => format(row.original.created_at, 'PPpp'),
        meta: {
            label: 'Created At',
            variant: 'dateRange',
            icon: CalendarIcon,
        },
        enableSorting: true,
    },

    {
        id: 'actions',
        cell: function Cell({ row }) {
            const [open, setOpen] = useState(false);

            const valuesToCopy = `Report Date: ${format(row.original.report_date, 'PPP')}, Hematocrit: ${row.original.hematocrit}%, Hemoglobin: ${row.original.hemoglobin} g/dL, RBC: ${row.original.red_blood_cell_count} /mm, MCV: ${row.original.mean_corpuscular_volume} fL, MCH: ${row.original.mean_corpuscular_hemoglobin} pg, MCHC: ${row.original.mean_corpuscular_hemoglobin_concentration} g/dL, RDW: ${row.original.red_blood_cell_distribution_width}%, WBC: ${row.original.leukocyte_count} /mm, Rod Neutrophil Count: ${row.original.rod_neutrophil_count} /mm, Segmented Neutrophil Count: ${row.original.segmented_neutrophil_count} /mm, Lymphocyte Count: ${row.original.lymphocyte_count} /mm, Monocyte Count: ${row.original.monocyte_count} /mm, Eosinophil Count: ${row.original.eosinophil_count} /mm, Basophil Count: ${row.original.basophil_count} /mm, Metamyelocyte Count: ${row.original.metamyelocyte_count} /mm, Promyelocyte Count: ${row.original.promyelocyte_count} /mm, Atypical Cell Count: ${row.original.atypical_cell_count} /mm, Platelet Count: ${row.original.platelet_count} /mm, Created At: ${format(row.original.created_at, 'PPP p')}`;

            return (
                <Fragment>
                    <DropdownMenu modal={false}>
                        <DropdownMenuTrigger asChild>
                            <Button
                                aria-label="Open menu"
                                variant="ghost"
                                className="flex size-8 p-0 data-[state=open]:bg-muted"
                            >
                                <EllipsisIcon aria-hidden size={16} />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" className="w-40">
                            <DropdownMenuLabel>Copy Fields</DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <DropdownMenuItem asChild>
                                    <DropdownMenuCopyButton content={valuesToCopy}>Copy Values</DropdownMenuCopyButton>
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                            <DropdownMenuSeparator />
                            <DropdownMenuLabel>Actions</DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <DropdownMenuItem asChild>
                                    <Link
                                        className="block w-full"
                                        href={CompleteBloodCountController.edit(row.original.id)}
                                        as="button"
                                    >
                                        <EditIcon aria-hidden size={14} /> Edit
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem variant="destructive" onSelect={() => setOpen(true)}>
                                    <DeleteIcon aria-hidden size={14} /> Delete
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <ActionConfirmationDialog
                        title="Delete Record"
                        description="Are you sure you want to delete this record? This action cannot be undone."
                        open={open}
                        setOpen={setOpen}
                        method={'delete'}
                        route={CompleteBloodCountController.destroy(row.original.id)}
                    />
                </Fragment>
            );
        },
        size: 40,
        enableHiding: false,
    },
];
