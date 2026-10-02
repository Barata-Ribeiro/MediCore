<?php

namespace App\Models\Exams;

use App\Enums\ExamType;
use App\Models\MedicalFile;
use App\Models\User;
use Carbon\CarbonImmutable;
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
 * @property string $id
 * @property int $user_id
 * @property int $medical_file_id
 * @property ExamType $exam_type
 * @property string $direction
 * @property string $status
 * @property string $path
 * @property array<array-key, mixed> $headers
 * @property string $delimiter
 * @property int $offset
 * @property int $cursor
 * @property int $max_id
 * @property int $revision
 * @property int $processed
 * @property int $validated_rows
 * @property string|null $error_code
 * @property int|null $error_line
 * @property CarbonImmutable $expires_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read MedicalFile $medicalFile
 * @property-read User $user
 *
 * @method static \Database\Factories\Exams\ExamCsvTransferFactory factory($count = null, $state = [])
 * @method static Builder<static>|ExamCsvTransfer newModelQuery()
 * @method static Builder<static>|ExamCsvTransfer newQuery()
 * @method static Builder<static>|ExamCsvTransfer query()
 * @method static Builder<static>|ExamCsvTransfer whereCreatedAt($value)
 * @method static Builder<static>|ExamCsvTransfer whereCursor($value)
 * @method static Builder<static>|ExamCsvTransfer whereDelimiter($value)
 * @method static Builder<static>|ExamCsvTransfer whereDirection($value)
 * @method static Builder<static>|ExamCsvTransfer whereErrorCode($value)
 * @method static Builder<static>|ExamCsvTransfer whereErrorLine($value)
 * @method static Builder<static>|ExamCsvTransfer whereExamType($value)
 * @method static Builder<static>|ExamCsvTransfer whereExpiresAt($value)
 * @method static Builder<static>|ExamCsvTransfer whereHeaders($value)
 * @method static Builder<static>|ExamCsvTransfer whereId($value)
 * @method static Builder<static>|ExamCsvTransfer whereMaxId($value)
 * @method static Builder<static>|ExamCsvTransfer whereMedicalFileId($value)
 * @method static Builder<static>|ExamCsvTransfer whereOffset($value)
 * @method static Builder<static>|ExamCsvTransfer wherePath($value)
 * @method static Builder<static>|ExamCsvTransfer whereProcessed($value)
 * @method static Builder<static>|ExamCsvTransfer whereRevision($value)
 * @method static Builder<static>|ExamCsvTransfer whereStatus($value)
 * @method static Builder<static>|ExamCsvTransfer whereUpdatedAt($value)
 * @method static Builder<static>|ExamCsvTransfer whereUserId($value)
 * @method static Builder<static>|ExamCsvTransfer whereValidatedRows($value)
 *
 * @mixin \Eloquent
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
