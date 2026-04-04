/* eslint-disable @typescript-eslint/no-unused-vars */

import type { Auth } from '@/types/auth';

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            sidebarOpen: boolean;
            date: {
                now: string;
                displayDate: string;
                greeting: string;
            };
            [key: string]: unknown;
        };
    }
}

declare module '@tanstack/react-table' {
    interface ColumnMeta<TData extends RowData, TValue> {
        label?: string;
        placeholder?: string;
        variant?: FilterVariant;
        options?: Option[];
        range?: [number, number];
        unit?: string;
        icon?: React.FC<React.SVGProps<SVGSVGElement>>;
    }
}

export interface Option {
    label: string;
    value: string;
}

export type FilterVariant = 'text' | 'number' | 'range' | 'date' | 'dateRange' | 'boolean' | 'select' | 'multiSelect';
