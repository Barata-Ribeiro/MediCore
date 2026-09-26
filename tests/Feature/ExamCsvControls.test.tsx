import { createInertiaApp, router } from '@inertiajs/react';
import type { ComponentProps } from 'react';
import { renderToString } from 'react-dom/server';
import { afterEach, expect, it, vi } from 'vite-plus/test';
import { exportMethod, importMethod } from '../../resources/js/actions/App/Http/Controllers/Exams/ExamCsvController';
import ExamCsvTransfers from '../../resources/js/components/application/exam-csv-transfers';
import DataTableExportData from '../../resources/js/components/table/data-table-export-data';
import DataTableImportData from '../../resources/js/components/table/data-table-import-data';

const exportAction = vi.hoisted(() => ({ click: undefined as (() => void) | undefined }));

vi.mock('../../resources/js/components/ui/dropdown-menu', async (importOriginal) => {
    const original = await importOriginal<typeof import('../../resources/js/components/ui/dropdown-menu')>();
    return {
        ...original,
        DropdownMenuContent: ({ children }: ComponentProps<typeof original.DropdownMenuContent>) => (
            <div>{children}</div>
        ),
        DropdownMenuItem: ({ onClick, children }: ComponentProps<typeof original.DropdownMenuItem>) => {
            exportAction.click = onClick as () => void;
            return <div>{children}</div>;
        },
    };
});

afterEach(() => {
    vi.restoreAllMocks();
    exportAction.click = undefined;
});

async function renderControls() {
    return createInertiaApp({
        page: {
            component: 'CsvTest',
            url: '/exams/glucose?page=3&per_page=10',
            version: null,
            clearHistory: false,
            encryptHistory: false,
            props: {
                errors: {},
                lang: { exam_csv: { import: 'Import CSV', import_help: 'Use an exported CSV as a template.' } },
            },
        },
        render: renderToString,
        resolve: () => () => (
            <>
                <DataTableImportData route={importMethod('glucose')} />
                <DataTableExportData csv={exportMethod('glucose')} />
            </>
        ),
        setup: ({ App, props }) => <App {...props} />,
    });
}

it('requests a queued export with POST without sending the current table page', async () => {
    const post = vi.spyOn(router, 'post').mockImplementation(() => {});
    await renderControls();
    exportAction.click?.();

    expect(post).toHaveBeenCalledWith(
        { url: '/exams/glucose/csv/export', method: 'post' },
        {},
        expect.objectContaining({ preserveScroll: true }),
    );
});

it('renders an accessible CSV import control with format guidance', async () => {
    const result = await renderControls();
    expect(result.body).toContain('Import CSV');
    expect(result.body).toContain('accept=".csv,text/csv"');
    expect(result.body).toContain('Use an exported CSV as a template.');
});

it('does not initialize Echo or access the browser while rendering the global control on the server', () => {
    expect(renderToString(<ExamCsvTransfers />)).toBe('');
});
