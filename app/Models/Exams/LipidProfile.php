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
 * @property float $total_cholesterol Total Cholesterol
 * @property float $hdl_cholesterol High-Density Lipoprotein Cholesterol
 * @property float $ldl_cholesterol Low-Density Lipoprotein Cholesterol
 * @property float $vldl_cholesterol Very Low-Density Lipoprotein Cholesterol
 * @property float $triglycerides Triglycerides
 * @property CarbonImmutable $report_date Date of the lipid profile report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereHdlCholesterol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereLdlCholesterol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereTotalCholesterol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereTriglycerides($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereVldlCholesterol($value)
 *
 * @mixin \Eloquent
 */
#[Table('lipid_profiles')]
#[Touches(['medicalFile'])]
#[Fillable(['total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides', 'report_date', 'medical_file_id'])]
class LipidProfile extends Model
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
