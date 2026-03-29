import DataTableColumnHeader from '@/components/table/data-table-column-header';
import type { LipidProfile } from '@/types/application/exams/lipid-profile';
import type { ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns';
import { CalendarIcon } from 'lucide-react';

export const columns: ColumnDef<LipidProfile>[] = [
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
        cell: ({ row }) => format(row.original.report_date, 'PPpp'),
        meta: {
            label: 'Report Date',
            variant: 'dateRange',
            icon: CalendarIcon,
        },
        enableSorting: true,
    },
    {
        accessorKey: 'Total Cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Total Cholesterol" />,
        enableSorting: true,
    },
    {
        accessorKey: 'HDL Cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="HDL Cholesterol" />,
        enableSorting: true,
    },
    {
        accessorKey: 'LDL Cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="LDL Cholesterol" />,
        enableSorting: true,
    },
    {
        accessorKey: 'VLDL Cholesterol',
        header: ({ column }) => <DataTableColumnHeader column={column} title="VLDL Cholesterol" />,
        enableSorting: true,
    },
    {
        accessorKey: 'Triglycerides',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Triglycerides" />,
        enableSorting: true,
    },
    {
        accessorKey: 'Created At',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Created At" />,
        cell: ({ row }) => format(row.original.created_at, 'PPpp'),
        meta: {
            label: 'Created At',
            variant: 'dateRange',
            icon: CalendarIcon,
        },
        enableSorting: true,
    },

    // TODO: Add action column with view/edit/delete buttons
];
