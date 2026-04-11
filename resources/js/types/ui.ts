import type { BreadcrumbItem } from '@/types/navigation';
import type { ReactNode } from 'react';

export type AppLayoutProps = {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
};

export type AppVariant = 'header' | 'sidebar';

export type AuthLayoutProps = {
    children?: ReactNode;
    name?: string;
    title?: string;
    description?: string;
};

export type FlashToast = {
    type: 'success' | 'info' | 'warning' | 'error';
    message: string;
};

export type ChartData = {
    x_axis_label: string;
    datasets: Record<string, { label: string; data: number }>;
};
