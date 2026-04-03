import DataTableColumnHeader from '@/components/table/data-table-column-header';
import type { Glucose } from '@/types/application/exams/glucose';
import type { ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns/format';
import { CalendarIcon } from 'lucide-react';

export const columns: ColumnDef<Glucose>[] = [
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
        accessorKey: 'glucose_level',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Glucose Level" />,
        cell: ({ row }) => `${row.original.glucose_level} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'glycated_hemoglobin',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Glycated Hemoglobin" />,
        cell: ({ row }) => `${row.original.glycated_hemoglobin}%`,
        enableSorting: true,
    },
    {
        accessorKey: 'estimated_average_glucose',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Estimated Average Glucose" />,
        cell: ({ row }) => `${row.original.estimated_average_glucose} mg/dL`,
        enableSorting: true,
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Created At" />,
        cell: ({ row }) => format(row.original.created_at, 'PPP p'),
        meta: {
            label: 'Created At',
            variant: 'dateRange',
            icon: CalendarIcon,
        },
        enableSorting: true,
    },
];
