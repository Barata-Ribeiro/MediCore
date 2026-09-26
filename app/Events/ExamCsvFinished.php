<?php

namespace App\Events;

use App\Models\Exams\ExamCsvTransfer;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;

class ExamCsvFinished implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable;

    public string $queue = 'default';

    public int $tries = 4;

    /** @var list<int> */
    public array $backoff = [10, 30, 60];

    public function __construct(public string $transferId) {}

    public function broadcastWhen(): bool
    {
        return ExamCsvTransfer::query()->whereKey($this->transferId)->where('expires_at', '>', now())->exists();
    }

    /** @return list<PrivateChannel> */
    public function broadcastOn(): array
    {
        $transfer = ExamCsvTransfer::query()->findOrFail($this->transferId);

        return [new PrivateChannel('App.Models.User.'.$transfer->user_id)];
    }

    /** @return array<string, mixed> */
    public function broadcastWith(): array
    {
        return ExamCsvTransfer::query()->findOrFail($this->transferId)->summary();
    }
}
