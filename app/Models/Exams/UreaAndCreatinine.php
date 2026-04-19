<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $urea_level Urea level
 * @property float $creatinine_level Creatinine level
 * @property CarbonImmutable $report_date Date of the Urea and Creatinine report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read float|null $urea_creatinine_ratio
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereCreatinineLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereUreaLevel($value)
 *
 * @mixin \Eloquent
 */
#[Table('urea_and_creatinines')]
#[Touches(['medicalFile'])]
#[Fillable(['urea_level', 'creatinine_level', 'report_date', 'medical_file_id'])]
#[Appends(['urea_creatinine_ratio'])]
class UreaAndCreatinine extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'report_date' => 'date:Y-m-d',
    ];

    public function getUreaCreatinineRatioAttribute(): ?float
    {
        if ($this->creatinine_level == 0) {
            return null;
        }

        return $this->urea_level / $this->creatinine_level;
    }

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
