<?php

namespace App\Jobs;

use App\Events\ExamCsvFinished;
use App\Exceptions\InvalidExamCsv;
use App\Models\Exams\ExamCsvTransfer;
use App\Services\Exams\ExamCsvService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessExamCsv implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 4;

    public int $timeout = 60;

    public int $uniqueFor = 3600;

    /** @var list<int> */
    public array $backoff = [10, 30, 60];

    public function __construct(public string $transferId, public int $revision)
    {
        $this->onQueue('default');
        $this->afterCommit();
    }

    public function uniqueId(): string
    {
        return $this->transferId.':'.$this->revision;
    }

    public function handle(ExamCsvService $service): void
    {
        try {
            $transfer = DB::transaction(function () use ($service): ?ExamCsvTransfer {
                $transfer = ExamCsvTransfer::query()->lockForUpdate()->find($this->transferId);
                if ($transfer === null || $transfer->expires_at->isPast()) {
                    return null;
                }
                if (! $transfer->finished() && $transfer->revision === $this->revision) {
                    $service->processChunk($transfer);
                }

                return $transfer;
            });
        } catch (InvalidExamCsv $exception) {
            $this->fail($exception);

            return;
        }

        if ($transfer === null) {
            return;
        }

        if ($transfer->finished()) {
            if ($transfer->direction === 'import' || $transfer->status === 'failed') {
                Storage::disk('local')->delete($transfer->path);
            }
            ExamCsvFinished::dispatch($transfer->id);
        } else {
            self::dispatch($transfer->id, $transfer->revision)->afterCommit();
        }
    }

    public function failed(?Throwable $exception): void
    {
        $transfer = DB::transaction(function () use ($exception): ?ExamCsvTransfer {
            $transfer = ExamCsvTransfer::query()->lockForUpdate()->find($this->transferId);
            if ($transfer === null || $transfer->finished() || $transfer->revision !== $this->revision) {
                return null;
            }

            $transfer->update([
                'status' => 'failed',
                'error_code' => $exception instanceof InvalidExamCsv ? $exception->errorCode : 'processing_failed',
                'error_line' => $exception instanceof InvalidExamCsv ? $exception->rowNumber : null,
                'expires_at' => now()->addDay(),
            ]);

            return $transfer;
        });

        if ($transfer !== null) {
            Storage::disk('local')->delete($transfer->path);
            ExamCsvFinished::dispatch($transfer->id);
        }
    }
}
