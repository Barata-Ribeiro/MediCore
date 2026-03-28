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

export type FlashPayload = {
    success?: string | null;
    error?: string | null;
    warning?: string | null;
    info?: string | null;
    [key: string]: string | null | undefined;
};
