export type ExamCsvTransfer = {
    id: string;
    exam_type: string;
    direction: 'import' | 'export';
    status: 'validating' | 'importing' | 'exporting' | 'completed' | 'failed';
    processed: number;
    error_code: 'invalid_row' | 'empty_file' | 'processing_failed' | null;
    error_line: number | null;
    expires_at: string;
};
