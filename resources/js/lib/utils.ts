import type { InertiaLinkProps } from '@inertiajs/react';
import type { ClassValue } from 'clsx';
import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(url: NonNullable<InertiaLinkProps['href']>): string {
    return typeof url === 'string' ? url : url.url;
}

export function buildParams(overrides: Record<string, string | number | boolean | undefined> = {}) {
    const params: Record<string, string | number | boolean | undefined> = {};

    const urlParams = new URLSearchParams(globalThis.window.location.search);

    for (const [key, value] of urlParams.entries()) {
        params[key] = value;
    }

    for (const k of Object.keys(overrides)) {
        const v = overrides[k];

        if (v === undefined || v === null || v === '') {
            params[k] = undefined;
        } else {
            params[k] = v;
        }
    }

    return params;
}

export function normalizeString(str: string) {
    return str
        .toLowerCase()
        .trim()
        .normalize('NFD') // Normalize to decomposed form
        .replaceAll(/[\u0300-\u036f]/g, '') // Remove diacritics
        .replaceAll(/[_\-\s]+/g, ' ') // convert underscores, dashes and repeated whitespace to single space
        .replaceAll(/[^\p{L}\p{N} ]+/gu, '') // Remove non-letter/number characters (keep spaces) - Unicode aware
        .replaceAll(/(^|\s)\p{L}/gu, (match) => match.toUpperCase()); // Capitalize first letter of each word - Unicode aware
}
