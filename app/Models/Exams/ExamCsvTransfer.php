<?php

namespace App\Models\Exams;

use App\Enums\ExamType;
use App\Models\MedicalFile;
use App\Models\User;
use Database\Factories\Exams\ExamCsvTransferFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

use function in_array;

/**
 * @mixin IdeHelperExamCsvTransfer
 */
#[Fillable(['user_id', 'medical_file_id', 'exam_type', 'direction', 'status', 'path', 'headers', 'delimiter', 'offset', 'cursor', 'max_id', 'revision', 'processed', 'validated_rows', 'error_code', 'error_line', 'expires_at'])]
class ExamCsvTransfer extends Model
{
    /** @use HasFactory<ExamCsvTransferFactory> */
    use HasFactory, HasUuids, Prunable;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['exam_type' => ExamType::class, 'headers' => 'array', 'expires_at' => 'immutable_datetime'];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<MedicalFile, $this> */
    public function medicalFile(): BelongsTo
    {
        return $this->belongsTo(MedicalFile::class);
    }

    public function finished(): bool
    {
        return in_array($this->status, ['completed', 'failed'], true);
    }

    /** @return array{id: string, exam_type: string, direction: string, status: string, processed: int, error_code: ?string, error_line: ?int, expires_at: string} */
    public function summary(): array
    {
        return [
            'id' => $this->id,
            'exam_type' => $this->exam_type->value,
            'direction' => $this->direction,
            'status' => $this->status,
            'processed' => $this->processed,
            'error_code' => $this->error_code,
            'error_line' => $this->error_line,
            'expires_at' => $this->expires_at->toISOString(),
        ];
    }

    /** @return Builder<static> */
    public function prunable(): Builder
    {
        return $this->newQueryWithoutRelationships()->where('expires_at', '<=', now());
    }

    protected function pruning(): void
    {
        Storage::disk('local')->delete($this->path);
    }
}
