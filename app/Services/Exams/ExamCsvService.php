<?php

namespace App\Services\Exams;

use App\Enums\ExamType;
use App\Exceptions\InvalidExamCsv;
use App\Models\Exams\ExamCsvTransfer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ExamCsvService
{
    private const int CHUNK_SIZE = 250;

    /** @return array{headers: list<string>, delimiter: string, offset: int} */
    public function header(string $path, ExamType $examType): array
    {
        $stream = $this->open($path, 'rb');

        try {
            foreach ([',', ';'] as $delimiter) {
                rewind($stream);
                $headers = fgetcsv($stream, 8192, $delimiter, '"', '');
                if ($headers === false) {
                    continue;
                }

                $headers = array_map(fn (?string $value): string => trim($value ?? ''), $headers);
                $headers[0] = preg_replace('/^\x{FEFF}/u', '', $headers[0]) ?? $headers[0];
                $expected = $examType->headers();

                if (count($headers) === count($expected) && count(array_unique($headers)) === count($expected)
                    && array_diff($expected, $headers) === []) {
                    return ['headers' => $headers, 'delimiter' => $delimiter, 'offset' => $this->position($stream)];
                }
            }
        } finally {
            fclose($stream);
        }

        throw ValidationException::withMessages([
            'file' => __('exam_csv.headers_mismatch', ['columns' => implode(', ', $examType->headers())]),
        ]);
    }

    public function processChunk(ExamCsvTransfer $transfer): void
    {
        if ($transfer->direction === 'import') {
            $this->importChunk($transfer);
        } else {
            $this->exportChunk($transfer);
        }

        $transfer->revision++;
        if ($transfer->finished()) {
            $transfer->expires_at = now()->addDay();
        }
        $transfer->save();
    }

    private function importChunk(ExamCsvTransfer $transfer): void
    {
        $stream = $this->open(Storage::disk('local')->path($transfer->path), 'rb');
        $validating = $transfer->status === 'validating';
        $rules = $transfer->exam_type->rules();
        $rules['report_date'] = ['required', 'date_format:Y-m-d'];

        try {
            if (fseek($stream, $transfer->offset) !== 0) {
                throw new RuntimeException('Cannot seek CSV input.');
            }

            for ($index = 0; $index < self::CHUNK_SIZE; $index++) {
                $values = fgetcsv($stream, 8192, $transfer->delimiter, '"', '');
                if ($values === false) {
                    if (! feof($stream)) {
                        throw new RuntimeException('Cannot read CSV input.');
                    }
                    if ($validating) {
                        if ($transfer->validated_rows === 0) {
                            throw new InvalidExamCsv('empty_file');
                        }
                        $header = $this->header(Storage::disk('local')->path($transfer->path), $transfer->exam_type);
                        $transfer->status = 'importing';
                        $transfer->offset = $header['offset'];
                        $transfer->cursor = 0;
                    } else {
                        $transfer->status = 'completed';
                    }

                    return;
                }

                $transfer->cursor++;
                $transfer->offset = $this->position($stream);
                if ($values === [null]) {
                    continue;
                }
                if (count($values) !== count($transfer->headers)) {
                    throw new InvalidExamCsv('invalid_row', $transfer->cursor + 1);
                }

                $row = array_combine($transfer->headers, array_map(fn (?string $value): string => trim($value ?? ''), $values));
                $validator = Validator::make($row, $rules);
                if ($validator->fails()) {
                    throw new InvalidExamCsv('invalid_row', $transfer->cursor + 1);
                }
                foreach ($row as $field => $value) {
                    if ($field !== 'report_date' && ! is_finite((float) $value)) {
                        throw new InvalidExamCsv('invalid_row', $transfer->cursor + 1);
                    }
                }

                if ($validating) {
                    $transfer->validated_rows++;
                } else {
                    $transfer->exam_type->model()::query()->create([
                        ...$validator->validated(),
                        'medical_file_id' => $transfer->medical_file_id,
                    ]);
                    $transfer->processed++;
                }
            }
        } finally {
            fclose($stream);
        }
    }

    private function exportChunk(ExamCsvTransfer $transfer): void
    {
        $disk = Storage::disk('local');
        if (! $disk->makeDirectory('exam-csv')) {
            throw new RuntimeException('Cannot create CSV directory.');
        }
        $stream = $this->open($disk->path($transfer->path), $transfer->offset === 0 ? 'c+b' : 'r+b');

        try {
            $stat = fstat($stream);
            if ($stat === false || $stat['size'] < $transfer->offset) {
                throw new RuntimeException('CSV output is shorter than its committed checkpoint.');
            }
            if ($transfer->offset < 0 || ! ftruncate($stream, $transfer->offset) || fseek($stream, $transfer->offset) !== 0) {
                throw new RuntimeException('Cannot restore CSV checkpoint.');
            }
            if ($transfer->offset === 0) {
                $this->writeRow($stream, $transfer->headers);
            }

            $records = $transfer->exam_type->model()::query()
                ->where('medical_file_id', $transfer->medical_file_id)
                ->where('id', '>', $transfer->cursor)
                ->where('id', '<=', $transfer->max_id)
                ->orderBy('id')
                ->limit(self::CHUNK_SIZE)
                ->get(['id', ...$transfer->headers]);

            foreach ($records as $record) {
                $this->writeRow($stream, array_map(
                    fn (string $field): string => (string) $record->getRawOriginal($field),
                    $transfer->headers,
                ));
                $transfer->cursor = (int) $record->getKey();
                $transfer->processed++;
            }

            if (! fflush($stream)) {
                throw new RuntimeException('Cannot flush CSV output.');
            }
            $transfer->offset = $this->position($stream);
            if ($records->count() < self::CHUNK_SIZE) {
                $transfer->status = 'completed';
            }
        } finally {
            fclose($stream);
        }
    }

    /** @return resource */
    private function open(string $path, string $mode)
    {
        $stream = fopen($path, $mode);
        if ($stream === false) {
            throw new RuntimeException('Cannot open CSV file.');
        }

        return $stream;
    }

    /** @param resource $stream */
    private function position($stream): int
    {
        $position = ftell($stream);
        if ($position === false) {
            throw new RuntimeException('Cannot read CSV position.');
        }

        return $position;
    }

    /**
     * @param  resource  $stream
     * @param  list<string>  $values
     */
    private function writeRow($stream, array $values): void
    {
        if (fputcsv($stream, $values, ',', '"', '', "\r\n") === false) {
            throw new RuntimeException('Cannot write CSV output.');
        }
    }
}
