import type { DataTableColumnMeta } from '@/types/data-table';
import {
    columnFilteringFeature,
    columnPinningFeature,
    columnSizingFeature,
    columnVisibilityFeature,
    globalFilteringFeature,
    metaHelper,
    rowPaginationFeature,
    rowSortingFeature,
    tableFeatures,
} from '@tanstack/react-table';

export const features = tableFeatures({
    columnFilteringFeature,
    globalFilteringFeature,
    columnPinningFeature,
    columnSizingFeature,
    columnVisibilityFeature,
    rowPaginationFeature,
    rowSortingFeature,
    columnMeta: metaHelper<DataTableColumnMeta>(),
});
