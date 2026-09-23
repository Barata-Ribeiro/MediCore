import type { features } from '@/hooks/features';
import type { Column as TableColumn, ColumnDef as TableColumnDef, ColumnFilter, RowData } from '@tanstack/react-table';

export type FilterVariant = 'text' | 'number' | 'date' | 'boolean' | 'select' | 'multi-select';
export type FilterOperator =
    | 'includesString'
    | 'notIncludesString'
    | 'startsWith'
    | 'endsWith'
    | 'equalsString'
    | 'notEqualsString'
    | 'equals'
    | 'notEquals'
    | 'lessThan'
    | 'lessThanOrEqualTo'
    | 'greaterThan'
    | 'greaterThanOrEqualTo'
    | 'inRange'
    | 'isRelativeToToday'
    | 'isEmpty'
    | 'isNotEmpty';
export interface ExtendedColumnFilter extends ColumnFilter {
    filterId: string;
    operator: FilterOperator;
    value: string | string[];
    joinOperator: 'and' | 'or';
}
export interface DataTableColumnMeta {
    label?: string;
    variant?: FilterVariant;
    options?: { label: string; value: string }[];
    placeholder?: string;
    range?: [number, number];
    unit?: string;
    icon?: React.ComponentType<React.ComponentProps<'svg'>>;
}
export type Column<TData extends RowData, TValue = unknown> = TableColumn<typeof features, TData, TValue>;
export type ColumnDef<TData extends RowData, TValue = unknown> = TableColumnDef<typeof features, TData, TValue>;
