import DataTableColumnHeader from '@/components/table/data-table-column-header';
import type { VitaminD3 } from '@/types/application/exams/vitamin-d3';
import type { ColumnDef } from '@tanstack/react-table';
import { format } from 'date-fns/format';
import { CalendarIcon } from 'lucide-react';

export const columns: ColumnDef<VitaminD3>[] = [
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
        accessorKey: 'twenty_five_hydroxyvitamin_d3',
        header: ({ column }) => <DataTableColumnHeader column={column} title="Vitamin D3" />,
        cell: ({ row }) => `${row.original.twenty_five_hydroxyvitamin_d3} ng/mL`,
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

    // TODO: Implement actions column
];
