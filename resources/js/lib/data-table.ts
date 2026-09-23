import type { ExtendedColumnFilter, FilterOperator, FilterVariant } from '@/types/data-table';

// Server operators such as isEmpty carry an empty value that Table must retain.
export const serverFilter = Object.assign(() => true, { autoRemove: () => false });

const operators: Record<FilterVariant, FilterOperator[]> = {
    text: [
        'includesString',
        'notIncludesString',
        'startsWith',
        'endsWith',
        'equalsString',
        'notEqualsString',
        'isEmpty',
        'isNotEmpty',
    ],
    number: ['equals', 'notEquals', 'lessThan', 'lessThanOrEqualTo', 'greaterThan', 'greaterThanOrEqualTo', 'inRange'],
    date: [
        'equals',
        'notEquals',
        'lessThan',
        'lessThanOrEqualTo',
        'greaterThan',
        'greaterThanOrEqualTo',
        'inRange',
        'isRelativeToToday',
        'isEmpty',
        'isNotEmpty',
    ],
    boolean: ['equals', 'notEquals'],
    select: ['equals', 'notEquals', 'isEmpty', 'isNotEmpty'],
    'multi-select': ['equals', 'notEquals', 'isEmpty', 'isNotEmpty'],
};

export function getFilterOperators(variant: FilterVariant, translate: (key: string) => string) {
    return operators[variant].map((value) => ({
        value,
        label: translate(`main.data_table.operators.${variant === 'date' ? 'date_' : ''}${value}`),
    }));
}

export function isActiveFilter(filter: ExtendedColumnFilter) {
    if (filter.operator === 'isEmpty' || filter.operator === 'isNotEmpty') return true;
    if (Array.isArray(filter.value)) return filter.value.some((value) => value !== '');
    return filter.value !== '';
}

export function parseTableState<T>(value: string | null, fallback: T): T {
    if (!value) return fallback;
    try {
        const parsed: unknown = JSON.parse(value);
        return Array.isArray(parsed) ? (parsed as T) : fallback;
    } catch {
        return fallback;
    }
}
