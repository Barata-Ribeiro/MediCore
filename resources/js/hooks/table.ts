import { TextCell, DateCell } from '@/components/table/cell-components';
import DataTableFilterList from '@/components/table/data-table-filter-list';
import DataTablePagination from '@/components/table/data-table-pagination';
import DataTableSortList from '@/components/table/data-table-sort-list';
import DataTableViewOptions from '@/components/table/data-table-view-options';
import { ColumnHeader } from '@/components/table/header-components';
import { features } from '@/hooks/features';
import { serverFilter } from '@/lib/data-table';
import { tableContext, cellContext, headerContext } from '@/hooks/table-context';
import { createTableHook } from '@tanstack/react-table';
import { createElement } from 'react';

export const { createAppColumnHelper, useAppTable } = createTableHook({
    features,
    tableContext,
    cellContext,
    headerContext,
    manualFiltering: true,
    manualSorting: true,
    manualPagination: true,
    autoResetPageIndex: false,
    maxMultiSortColCount: 10,
    defaultColumn: {
        size: 150,
        minSize: 60,
        cell: TextCell,
        header: () => createElement(ColumnHeader),
        filterFn: serverFilter,
    },
    tableComponents: {
        FilterList: DataTableFilterList,
        SortList: DataTableSortList,
        Pagination: DataTablePagination,
        ViewOptions: DataTableViewOptions,
    },
    cellComponents: { TextCell, DateCell },
    headerComponents: { ColumnHeader },
});
