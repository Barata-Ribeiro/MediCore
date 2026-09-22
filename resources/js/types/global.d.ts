/* eslint-disable @typescript-eslint/no-unused-vars */

import type { Auth } from '@/types/auth';
import '@inertiajs/core';

declare module 'react' {
    interface InputHTMLAttributes<T> {
        passwordrules?: string;
    }
}

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
