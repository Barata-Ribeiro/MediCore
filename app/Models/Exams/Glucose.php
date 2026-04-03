<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $glucose_level
 * @property float $glycated_hemoglobin Glycated Hemoglobin (HbA1c)
 * @property float $estimated_average_glucose Estimated Average Glucose (eAG)
 * @property CarbonImmutable $report_date Date of the glucose report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereEstimatedAverageGlucose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereGlucoseLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereGlycatedHemoglobin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Touches(['medicalFile'])]
#[Fillable(['glucose_level', 'glycated_hemoglobin', 'estimated_average_glucose', 'report_date', 'medical_file_id'])]
class Glucose extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'report_date' => 'date',
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
