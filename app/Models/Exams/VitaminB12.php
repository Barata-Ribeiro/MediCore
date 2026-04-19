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
 * @property float $vitamin_b12_level Vitamin B12 level
 * @property CarbonImmutable $report_date Date of the Vitamin B12 report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereVitaminB12Level($value)
 *
 * @mixin \Eloquent
 */
#[Table('vitamin_b12_s')]
#[Touches(['medicalFile'])]
#[Fillable(['vitamin_b12_level', 'report_date', 'medical_file_id'])]
class VitaminB12 extends Model
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
