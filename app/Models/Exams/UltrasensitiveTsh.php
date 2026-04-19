<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $tsh_level Ultrasensitive TSH level
 * @property CarbonImmutable $report_date Date of the Urea and Creatinine report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereTshLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Table('ultrasensitive_tshs')]
#[Touches(['medicalFile'])]
#[Fillable(['tsh_level', 'report_date', 'medical_file_id'])]
class UltrasensitiveTsh extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'report_date' => 'date:Y-m-d',
    ];

    public function medicalFile(): BelongsTo
    {
        return $this->belongsTo(MedicalFile::class);
    }

    /**
     * Ensure report_date is saved as a date-only string (Y-m-d).
     */
    public function setReportDateAttribute(CarbonImmutable|string|\DateTimeInterface|null $value): void
    {
        if ($value === null) {
            $this->attributes['report_date'] = null;

            return;
        }

        $this->attributes['report_date'] = CarbonImmutable::parse($value)->toDateString();
    }
}
