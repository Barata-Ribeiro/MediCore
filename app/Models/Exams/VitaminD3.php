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
 * @property float $twenty_five_hydroxyvitamin_d3 25-Hydroxyvitamin D3 (25(OH)D3) level
 * @property CarbonImmutable $report_date Date of the glucose report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereTwentyFiveHydroxyvitaminD3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Touches(['medicalFile'])]
#[Fillable(['twenty_five_hydroxyvitamin_d3', 'report_date', 'medical_file_id'])]
class VitaminD3 extends Model
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
