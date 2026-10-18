<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Database\Factories\Exams\UreaAndCreatinineFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperUreaAndCreatinine
 */
#[Table('urea_and_creatinines')]
#[Touches(['medicalFile'])]
#[Fillable(['urea_level', 'creatinine_level', 'report_date', 'medical_file_id'])]
#[Appends(['urea_creatinine_ratio'])]
class UreaAndCreatinine extends Model
{
    /** @use HasFactory<UreaAndCreatinineFactory> */
    use HasFactory;

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

    /**
     * @return BelongsTo<MedicalFile, $this>
     */
    public function medicalFile(): BelongsTo
    {
        return $this->belongsTo(MedicalFile::class, 'medical_file_id', 'id');
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
